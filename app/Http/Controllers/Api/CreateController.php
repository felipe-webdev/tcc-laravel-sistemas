<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class CreateController extends Controller
{
  protected $PDO;


  public function __construct()
  {
    $this->PDO = DB::getPdo();
    $this->PDO->setAttribute($this->PDO::ATTR_ERRMODE,            $this->PDO::ERRMODE_EXCEPTION);
    $this->PDO->setAttribute($this->PDO::ATTR_DEFAULT_FETCH_MODE, $this->PDO::FETCH_ASSOC);
    $this->PDO->setAttribute($this->PDO::ATTR_EMULATE_PREPARES,   false);
  }


  public function insertEmployee (Request $request){
    $data     = json_decode($request->getContent());
    $employee = $data->employee;

    $sql1 = "INSERT INTO pessoas (
      id_tipo,
      id_entidade,
      nome,
      sobrenome,
      nascimento,
      cpf)
    VALUES( ?, ?, ?, ?, ?, ? );";

    try {
      $statement = $this->PDO->prepare($sql1);
      $success   = $statement->execute([
        $employee->person_type,
        $employee->id_entity,
        $employee->name,
        $employee->surname,
        $employee->birth_y.'-'.$employee->birth_m.'-'.$employee->birth_d,
        $employee->cpf
      ]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }

    if($success){
      $id_person = $this->PDO->lastInsertId();

      $sql2 = "INSERT INTO contatos (
        id_tipo,
        id_pessoa,
        contato)
      VALUES ( ?, ?, ? );";

      try {
        $statement = $this->PDO->prepare($sql2);
        if($employee->phone){$statement->execute([1, $id_person, $employee->phone]);}
        if($employee->email){$statement->execute([2, $id_person, $employee->email]);}
      } catch ( PDOException $e ){
        return response()->json([
          'success' => false,
          'error'   => 'sql',
          'msg'     => $e->getMessage()
        ]);
      }

      $sql3 = "INSERT INTO enderecos (
        id_tipo,
        id_pessoa,
        logradouro,
        numero,
        complemento_numero,
        bairro,
        cidade,
        estado,
        uf,
        cep)
      VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? );";

      try {
        $statement = $this->PDO->prepare($sql3);
        $statement->execute([
          $employee->address->address_type,
          $id_person,
          $employee->address->line,
          $employee->address->number,
          $employee->address->number_info,
          $employee->address->district,
          $employee->address->city,
          null,
          $employee->address->uf,
          $employee->address->cep
        ]);
      } catch ( PDOException $e ){
        return response()->json([
          'success' => false,
          'error'   => 'sql',
          'msg'     => $e->getMessage()
        ]);
      }

      $sql4 = "INSERT INTO funcionarios (
        id_pessoa,
        id_cargo,
        ativo)
      VALUES ( ?, ?, ? );";

      try {
        $statement = $this->PDO->prepare($sql4);
        $success2  = $statement->execute([
          $id_person,
          $employee->job_type,
          1 /* FUNCIONÁRIO ATIVO */
        ]);
      } catch ( PDOException $e ){
        return response()->json([
          'success' => false,
          'error'   => 'sql',
          'msg'     => $e->getMessage()
        ]);
      }

      if($success2){
        $id_employee = $this->PDO->lastInsertId();

        if(sizeof($employee->family) > 0){
          $sql5 = "INSERT INTO dependentes (
            id_tipo,
            id_pessoa,
            id_funcionario)
          VALUES ( ?, ?, ? );";

          for($i = 0; $i < sizeof($employee->family); $i++){
            try {
              $statement = $this->PDO->prepare($sql1); /* INSERT EM PESSOAS SQL1 */
              $success3  = $statement->execute([
                1, /* PESSOA FÍSICA */
                $employee->id_entity,
                $employee->family[$i]->name,
                $employee->family[$i]->surname,
                $employee->family[$i]->birth_y.'-'.$employee->family[$i]->birth_m.'-'.$employee->family[$i]->birth_d,
                $employee->family[$i]->cpf
              ]);
            } catch ( PDOException $e ){
              return response()->json([
                'success' => false,
                'error'   => 'sql',
                'msg'     => $e->getMessage()
              ]);
            }

            if($success3){
              $id_person2 = $this->PDO->lastInsertId();

              try {
                $statement = $this->PDO->prepare($sql5); /* INSERT EM DEPENDENTES SQL5 */
                $statement->execute([
                  $employee->family[$i]->id_family_type,
                  $id_person2,
                  $id_employee
                ]);
              } catch ( PDOException $e ){
                return response()->json([
                  'success' => false,
                  'error'   => 'sql',
                  'msg'     => $e->getMessage()
                ]);
              }
            }
          }
        }

        if($employee->user->create){
          $sql6 = "INSERT INTO usuarios (
            id_grupo,
            id_funcionario,
            usuario,
            senha)
          VALUES ( ?, ?, ?, ? );";

          try {
            $statement = $this->PDO->prepare($sql6);
            $statement->execute([
              $employee->user->id_user_group,
              $id_employee,
              $employee->user->user.''.$employee->user->postfix,
              password_hash($employee->user->pass, PASSWORD_DEFAULT)
            ]);
          } catch ( PDOException $e ){
            return response()->json([
              'success' => false,
              'error'   => 'sql',
              'msg'     => $e->getMessage()
            ]);
          }
        }
      }

      return response()->json([
        'success'     => true,
        'id_person'   => $id_person,
        'id_employee' => $id_employee,
      ]);
    }
  }


  public function insertFamily (Request $request){
    $data        = json_decode($request->getContent());
    $id_entity   = $data->id_entity;
    $id_employee = $data->id_employee;
    $family      = $data->family;

    $sql1 = "INSERT INTO pessoas (
      id_tipo,
      id_entidade,
      nome,
      sobrenome,
      nascimento,
      cpf)
    VALUES( ?, ?, ?, ?, ?, ? );";

    $sql2 = "INSERT INTO dependentes (
      id_tipo,
      id_pessoa,
      id_funcionario)
    VALUES ( ?, ?, ? );";

    if(sizeof($family) > 0){
      for($i = 0; $i < sizeof($family); $i++){
        try {
          $statement = $this->PDO->prepare($sql1); /* INSERT EM PESSOAS SQL1 */
          $success   = $statement->execute([
            1, /* PESSOA FÍSICA */
            $id_entity,
            $family[$i]->name,
            $family[$i]->surname,
            $family[$i]->birth_y.'-'.$family[$i]->birth_m.'-'.$family[$i]->birth_d,
            $family[$i]->cpf
          ]);
        } catch ( PDOException $e ){
          return response()->json([
            'success' => false,
            'error'   => 'sql',
            'msg'     => $e->getMessage()
          ]);
        }

        if($success){
          $id_person = $this->PDO->lastInsertId();

          try {
            $statement = $this->PDO->prepare($sql2); /* INSERT EM DEPENDENTES SQL2 */
            $statement->execute([
              $family[$i]->id_family_type,
              $id_person,
              $id_employee
            ]);
          } catch ( PDOException $e ){
            return response()->json([
              'success' => false,
              'error'   => 'sql',
              'msg'     => $e->getMessage()
            ]);
          }
        }
      }
    }

    return response()->json([
      'success' => true
    ]);
  }


  public function insertUser (Request $request){
    $data        = json_decode($request->getContent());
    $id_employee = $data->id_employee;
    $user        = $data->user;

    $sql = "INSERT INTO usuarios (
      id_grupo,
      id_funcionario,
      usuario,
      senha)
    VALUES ( ?, ?, ?, ? );";

    try {
      $statement = $this->PDO->prepare($sql);
      $statement->execute([
        $user->id_user_group,
        $id_employee,
        $user->user.''.$user->postfix,
        password_hash($user->pass, PASSWORD_DEFAULT)
      ]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }

    return response()->json([
      'success' => true
    ]);
  }


  public function insertEmail (Request $request){
    $data      = json_decode($request->getContent());
    $id_person = $data->id_person;
    $emails    = $data->emails;

    $sql = "INSERT INTO contatos (
      id_tipo,
      id_pessoa,
      contato,
      observacao)
    VALUES( ?, ?, ?, ? );";

    if(sizeof($emails) > 0){
      for($i = 0; $i < sizeof($emails); $i++){
        try {
          $statement = $this->PDO->prepare($sql); /* INSERT EM CONTATOS SQL */
          $statement->execute([
            $emails[$i]->id_contact_type,
            $id_person,
            $emails[$i]->email,
            $emails[$i]->obs
          ]);
        } catch ( PDOException $e ){
          return response()->json([
            'success' => false,
            'error'   => 'sql',
            'msg'     => $e->getMessage()
          ]);
        }
      }
    }

    return response()->json([
      'success' => true
    ]);
  }


  public function insertPhone (Request $request){
    $data      = json_decode($request->getContent());
    $id_person = $data->id_person;
    $phones    = $data->phones;

    $sql = "INSERT INTO contatos (
      id_tipo,
      id_pessoa,
      contato,
      observacao)
    VALUES( ?, ?, ?, ? );";

    if(sizeof($phones) > 0){
      for($i = 0; $i < sizeof($phones); $i++){
        try {
          $statement = $this->PDO->prepare($sql); /* INSERT EM CONTATOS SQL */
          $statement->execute([
            $phones[$i]->id_contact_type,
            $id_person,
            $phones[$i]->phone,
            $phones[$i]->obs
          ]);
        } catch ( PDOException $e ){
          return response()->json([
            'success' => false,
            'error'   => 'sql',
            'msg'     => $e->getMessage()
          ]);
        }
      }
    }

    return response()->json([
      'success' => true
    ]);
  }


  public function insertDepart (Request $request){
    $data = json_decode($request->getContent());
    $depart = $data->depart;

    $sql = "INSERT INTO areas (
      nome,
      observacao)
    VALUES( ?, ? );";

    try {
      $statement = $this->PDO->prepare($sql); /* INSERT EM AREAS SQL */
      $statement->execute([
        $depart->job_depart,
        $depart->obs,
      ]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }

    return response()->json([
      'success' => true
    ]);
  }


  public function insertJob (Request $request){
    $data = json_decode($request->getContent());
    $job = $data->job;

    $sql = "INSERT INTO cargos (
      id_area,
      nome,
      salario)
    VALUES( ?, ?, ? );";

    try {
      $statement = $this->PDO->prepare($sql); /* INSERT EM CARGOS SQL */
      $statement->execute([
        $job->id_job_depart,
        $job->job_type,
        $job->salary,
      ]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }

    return response()->json([
      'success' => true
    ]);
  }  


  public function insertImage (Request $request){
    $image = file_get_contents($request->file('image'));
    $id_person = $request->input('id_person');

    $sql = "UPDATE pessoas 
      SET imagem = ?
    WHERE id = ?;";

    try {
      $statement = $this->PDO->prepare($sql); /* UPDATE EM PESSOAS SQL */
      $statement->bindParam(1, $image, $this->PDO::PARAM_LOB);
      $statement->bindParam(2, $id_person, $this->PDO::PARAM_INT);
      $statement->execute();
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }

    return response()->json([
      'success' => true
    ]);
  } 
}