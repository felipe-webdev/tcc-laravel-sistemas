<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class UpdateController extends Controller
{
  protected $PDO;


  public function __construct()
  {
    $this->PDO = DB::getPdo();
    $this->PDO->setAttribute($this->PDO::ATTR_ERRMODE,            $this->PDO::ERRMODE_EXCEPTION);
    $this->PDO->setAttribute($this->PDO::ATTR_DEFAULT_FETCH_MODE, $this->PDO::FETCH_ASSOC);
    $this->PDO->setAttribute($this->PDO::ATTR_EMULATE_PREPARES,   false);
  }


  public function alterPass (Request $request){
    $data     = json_decode($request->getContent());
    $id_user  = $data->id_user;
    $old_pass = $data->old_pass;
    $new_pass = $data->new_pass;

    $sql = "SELECT senha AS pass FROM usuarios WHERE id = ?;";
    try {
      $statement = $this->PDO->prepare($sql);
      $statement->execute([$id_user]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $result = $statement->fetch();

    if(password_verify($new_pass, $result['pass'])){
      return response()->json([
        'success' => false,
        'error'   => 'new_pass'
      ]);
    }

    if(password_verify($old_pass, $result['pass'])){
      $sql2 = "UPDATE usuarios SET senha = ? WHERE id = ?;";
      try {
        $statement2 = $this->PDO->prepare($sql2);
        $statement2->execute([password_hash($new_pass, PASSWORD_DEFAULT), $id_user]);
      } catch ( PDOException $e ){
        return response()->json([
          'success' => false,
          'error'   => 'sql',
          'msg'     => $e->getMessage()
        ]);
      }

      if($statement2->rowCount()){
        return response()->json([
          'success'  => true,
          'affected' =>$statement2->rowCount()
        ]);
      }
    } else {
      return response()->json([
        'success' => false,
        'error'   => 'old_pass'
      ]);
    }
  }


  public function resetPass (Request $request){
    $data = json_decode($request->getContent());
    $id_user = $data->id_user;

    $sql = "UPDATE usuarios SET senha = ? WHERE id = ?;";
    try {
      $statement = $this->PDO->prepare($sql);
      $statement->execute([password_hash('mestre123', PASSWORD_DEFAULT), $id_user]); // TENTAR PASSAR PELO ENV OU PELA BASE
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }

    if($statement->rowCount()){
      return response()->json([
        'success'  => true,
        'affected' => $statement->rowCount()
      ]);
    } else {
      return response()->json([
        'success' => false,
        'error'   => 'no_row_affected'
      ]);
    }
  }


  public function updateFamily (Request $request){
    $data   = json_decode($request->getContent());
    $family = $data->family;

    $sql1 = "UPDATE pessoas
      SET
        nome       = ?,
        sobrenome  = ?,
        nascimento = ?,
        cpf        = ?
      WHERE id     = ?;";

    try {
      $statement = $this->PDO->prepare($sql1); /* UPDATE EM PESSOAS SQL1 */
      $statement->execute([
        $family->name,
        $family->surname,
        $family->birth_y.'-'.$family->birth_m.'-'.$family->birth_d,
        $family->cpf,
        $family->id_person
      ]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }

    $sql2 = "UPDATE dependentes SET id_tipo = ? WHERE id = ?;";

    try {
      $statement = $this->PDO->prepare($sql2); /* UPDATE EM DEPENDENTES SQL2 */
      $statement->execute([
        $family->id_family_type,
        $family->id_family
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


  public function updateEmail (Request $request){
    $data  = json_decode($request->getContent());
    $email = $data->email;

    $sql = "UPDATE contatos
    SET
      contato    = ?,
      observacao = ?
    WHERE id     = ?;";

    try {
      $statement = $this->PDO->prepare($sql); /* UPDATE EM CONTATOS SQL */
      $statement->execute([
        $email->email,
        $email->obs,
        $email->id_email
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


  public function updatePhone (Request $request){
    $data  = json_decode($request->getContent());
    $phone = $data->phone;

    $sql = "UPDATE contatos
    SET
      contato    = ?,
      observacao = ?
    WHERE id     = ?;";

    try {
      $statement = $this->PDO->prepare($sql); /* UPDATE EM CONTATOS SQL */
      $statement->execute([
        $phone->phone,
        $phone->obs,
        $phone->id_phone
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


  public function updateEmployee (Request $request){
    $data     = json_decode($request->getContent());
    $new_data = $data->new_data;
    $part     = $data->part;

    switch($part){
      case 'personal':
        $sql = "UPDATE pessoas
        SET
          nome       = ?,
          sobrenome  = ?,
          nascimento = ?,
          cpf        = ?
        WHERE id     = ?;";
        try {
          $statement = $this->PDO->prepare($sql); /* UPDATE EM PESSOAS SQL */
          $statement->execute([
            $new_data->name,
            $new_data->surname,
            $new_data->birth_y.'-'.$new_data->birth_m.'-'.$new_data->birth_d,
            $new_data->cpf,
            $new_data->id_person
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

      case 'address':
        $sql = "UPDATE enderecos
        SET
          logradouro         = ?,
          numero             = ?,
          complemento_numero = ?,
          bairro             = ?,
          cidade             = ?,
          uf                 = ?,
          cep                = ?
        WHERE id             = ?;";
        try {
          $statement = $this->PDO->prepare($sql); /* UPDATE EM ENDEREÇOS SQL */
          $statement->execute([
            $new_data->line,
            $new_data->number,
            $new_data->number_info,
            $new_data->district,
            $new_data->city,
            $new_data->uf,
            $new_data->cep,
            $new_data->id_address,
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

      case 'job':
        $sql = "UPDATE funcionarios
        SET
          id_cargo = ?
        WHERE id   = ?;";
        try {
          $statement = $this->PDO->prepare($sql); /* UPDATE EM FUNCIONÁRIOS SQL */
          $statement->execute([
            $new_data->id_job_type,
            $new_data->id_employee
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

      case 'active':
        $sql = "UPDATE funcionarios
        SET
          ativo = ?
        WHERE id   = ?;";
        try {
          $statement = $this->PDO->prepare($sql); /* UPDATE EM FUNCIONÁRIOS SQL */
          $statement->execute([
            $new_data->active,
            $new_data->id_employee
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
  }


  public function updateDepart (Request $request){
    $data  = json_decode($request->getContent());
    $depart = $data->depart;

    $sql = "UPDATE areas
    SET
      nome       = ?,
      observacao = ?
    WHERE id     = ?;";

    try {
      $statement = $this->PDO->prepare($sql); /* UPDATE EM AREAS SQL */
      $statement->execute([
        $depart->job_depart,
        $depart->obs,
        $depart->id_job_depart
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


  public function updateJob (Request $request){
    $data  = json_decode($request->getContent());
    $job = $data->job;

    $sql = "UPDATE cargos
    SET
      id_area    = ?,
      nome       = ?,
      salario    = ?
    WHERE id     = ?;";

    try {
      $statement = $this->PDO->prepare($sql); /* UPDATE EM CONTATOS SQL */
      $statement->execute([
        $job->id_job_depart,
        $job->job_type,
        $job->salary,
        $job->id_job_type
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
}