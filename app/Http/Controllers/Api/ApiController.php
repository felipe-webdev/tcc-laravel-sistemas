<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use Session;

class ApiController extends Controller
{
  // public function login (Request $request){
  // }


  // public function logout (Request $request){
  // }


  public function getSessionUser (Request $request){
    if($request->session('authenticated'))
    {
      return response()->json([
          'session_started'=> true,
          'session_user'   => [
            'id_user'      => $request->session()->get('id_user'),
            'user'         => $request->session()->get('user'),
            'id_employee'  => $request->session()->get('id_employee'),
            'id_group'     => $request->session()->get('id_group'),
            'group'        => $request->session()->get('group'),
            'id_person'    => $request->session()->get('id_person'),
            'name_user'    => $request->session()->get('name_user'),
            'surname_user' => $request->session()->get('surname_user'),
            'id_entity'    => $request->session()->get('id_entity'),
            'entity'       => $request->session()->get('entity'),
            'postfix'      => $request->session()->get('postfix'),
          ]
        ]);
    } 
    else 
    {
      return response()->json([
        'session_started' => false
      ]);
    }
  }


  public function alterPass (Request $request){
    $data     = json_decode($request->getContent());
    $id_user  = $data->id_user;
    $old_pass = $data->old_pass;
    $new_pass = $data->new_pass;

    $PDO = DB::getPdo();

    $sql = "SELECT senha AS pass FROM usuarios WHERE id = ?;";
    try {
      $statement = $PDO->prepare($sql);
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
        $statement2 = $PDO->prepare($sql2);
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

    $PDO = DB::getPdo();

    $sql = "UPDATE usuarios SET senha = ? WHERE id = ?;";
    try {
      $statement = $PDO->prepare($sql);
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


  public function getSystemTypes (Request $request){
    $PDO = DB::getPdo();

    $result = $PDO->query("SELECT 
      descricao AS label,
      id        AS `value`
    FROM pessoas_tipos ORDER BY id;");
    $person = $result->fetchAll();

    $result = $PDO->query("SELECT 
      descricao AS label,
      id        AS `value`
    FROM dependentes_tipos ORDER BY id;");
    $family = $result->fetchAll();

    $result = $PDO->query("SELECT 
      descricao AS label,
      id        AS `value`
    FROM enderecos_tipos ORDER BY id;");
    $address = $result->fetchAll();

    $result = $PDO->query("SELECT 
      descricao AS label,
      id        AS `value`
    FROM contatos_tipos ORDER BY id;");
    $contact = $result->fetchAll();

    $result = $PDO->query("SELECT 
      nome AS label,
      id   AS `value`
    FROM usuarios_grupos ORDER BY id;");
    $user_group = $result->fetchAll();

    $result = $PDO->query("SELECT 
      nome AS label,
      id   AS `value`
    FROM cargos ORDER BY id;");
    $job = $result->fetchAll();

    $result = $PDO->query("SELECT 
      nome AS label,
      id   AS `value`
    FROM areas ORDER BY id;");
    $job_depart = $result->fetchAll();

    return response()->json([
      'person'     => $person,
      'family'     => $family,
      'contact '   => $contact,
      'address'    => $address,
      'user_group' => $user_group,
      'job'        => $job,
      'job_depart' => $job_depart
    ]);
  }


  public function countRecords (Request $request){
    $PDO = DB::getPdo();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM pessoas            AS p
    INNER JOIN funcionarios AS f
      ON f.id_pessoa = p.id;");
    $total = $result->fetchAll();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM pessoas            AS p
    INNER JOIN funcionarios AS f
      ON f.id_pessoa = p.id
    WHERE f.ativo = 1;");
    $active = $result->fetchAll();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM pessoas            AS p
    INNER JOIN funcionarios AS f
      ON f.id_pessoa = p.id
    WHERE f.ativo = 0;");
    $inactive = $result->fetchAll();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM dependentes        AS d
    INNER JOIN funcionarios AS f
      ON f.id = d.id_funcionario;");
    $total_family = $result->fetchAll();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM dependentes        AS d
    INNER JOIN funcionarios AS f
      ON f.id = d.id_funcionario
    WHERE f.ativo = 1;");
    $active_family = $result->fetchAll();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM dependentes        AS d
    INNER JOIN funcionarios AS f
      ON f.id = d.id_funcionario
    WHERE f.ativo = 0;");
    $inactive_family = $result->fetchAll();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM usuarios           AS u
    INNER JOIN funcionarios AS f
      ON f.id = u.id_funcionario;");
    $total_user = $result->fetchAll();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM usuarios           AS u
    INNER JOIN funcionarios AS f
      ON f.id = u.id_funcionario
    WHERE f.ativo = 1;");
    $active_user = $result->fetchAll();

    $result = $PDO->query("SELECT
      COUNT(*)              AS total
    FROM usuarios           AS u
    INNER JOIN funcionarios AS f
      ON f.id = u.id_funcionario
    WHERE f.ativo = 0;");
    $inactive_user = $result->fetchAll();

    return response()->json([
        'employee'   => [
          'total'    => $total[0]['total'],
          'active'   => $active[0]['total'],
          'inactive' => $inactive[0]['total']
        ],
        'family'     => [
          'total'    => $total_family[0]['total'],
          'active'   => $active_family[0]['total'],
          'inactive' => $inactive_family[0]['total']
        ],
        'user'       => [
          'total'    => $total_user[0]['total'],
          'active'   => $active_user[0]['total'],
          'inactive' => $inactive_user[0]['total']
        ]
      ]);
  }


  public function insertEmployee (Request $request){
    $data     = json_decode($request->getContent());
    $employee = $data->employee;

    $PDO = DB::getPdo();

    $sql1 = "INSERT INTO pessoas (
      id_tipo,
      id_entidade,
      nome,
      sobrenome,
      nascimento,
      cpf)
    VALUES( ?, ?, ?, ?, ?, ? );";

    try {
      $statement = $PDO->prepare($sql1);
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
      $id_person = $PDO->lastInsertId();

      $sql2 = "INSERT INTO contatos (
        id_tipo,
        id_pessoa,
        contato)
      VALUES ( ?, ?, ? );";

      try {
        $statement = $PDO->prepare($sql2);
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
        $statement = $PDO->prepare($sql3);
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
        $statement = $PDO->prepare($sql4);
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
        $id_employee = $PDO->lastInsertId();

        if(sizeof($employee->family) > 0){
          $sql5 = "INSERT INTO dependentes (
            id_tipo,
            id_pessoa,
            id_funcionario)
          VALUES ( ?, ?, ? );";

          for($i = 0; $i < sizeof($employee->family); $i++){
            try {
              $statement = $PDO->prepare($sql1); /* INSERT EM PESSOAS SQL1 */
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
              $id_person2 = $PDO->lastInsertId();

              try {
                $statement = $PDO->prepare($sql5); /* INSERT EM DEPENDENTES SQL5 */
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
            $statement = $PDO->prepare($sql6);
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
        'success' => true
      ]);
    }
  }


  public function insertFamily (Request $request){
    $data        = json_decode($request->getContent());
    $id_entity   = $data->id_entity;
    $id_employee = $data->id_employee;
    $family      = $data->family;

    $PDO = DB::getPdo();

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
          $statement = $PDO->prepare($sql1); /* INSERT EM PESSOAS SQL1 */
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
          $id_person = $PDO->lastInsertId();

          try {
            $statement = $PDO->prepare($sql2); /* INSERT EM DEPENDENTES SQL2 */
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


  public function updateFamily (Request $request){
    $data   = json_decode($request->getContent());
    $family = $data->family;

    $PDO = DB::getPdo();

    $sql1 = "UPDATE pessoas
      SET
        nome       = ?,
        sobrenome  = ?,
        nascimento = ?,
        cpf        = ?
      WHERE id     = ?;";

    try {
      $statement = $PDO->prepare($sql1); /* UPDATE EM PESSOAS SQL1 */
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
      $statement = $PDO->prepare($sql2); /* UPDATE EM DEPENDENTES SQL2 */
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


  public function deleteFamily (Request $request){
    $data      = json_decode($request->getContent());
    $id_family = $data->id_family;
    $id_person = $data->id_person;

    $PDO = DB::getPdo();

    $sql1 = "DELETE FROM dependentes WHERE id = ?;";

    try {
      $statement = $PDO->prepare($sql1); /* DELETE EM DEPENDENTES SQL1 */
      $statement->execute([
        $id_family
      ]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }

    $sql2 = "DELETE FROM pessoas WHERE id = ?;";

    try {
      $statement = $PDO->prepare($sql2); /* DELETE EM PESSOAS SQL2 */
      $statement->execute([
        $id_person
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


  public function insertUser (Request $request){
    $data        = json_decode($request->getContent());
    $id_employee = $data->id_employee;
    $user        = $data->user;

    $PDO = DB::getPdo();

    $sql = "INSERT INTO usuarios (
      id_grupo,
      id_funcionario,
      usuario,
      senha)
    VALUES ( ?, ?, ?, ? );";

    try {
      $statement = $PDO->prepare($sql);
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


  public function isUserAvailable (Request $request){
    $data = json_decode($request->getContent());
    $user = $data->user;

    $PDO = DB::getPdo();

    $sql = "SELECT COUNT(id) AS is_user FROM usuarios WHERE usuario = ?;";
    try {
      $statement = $PDO->prepare($sql);
      $statement->execute([$user]);
    } catch ( PDOException $e ){
      return response()->json([
        'available' => false,
        'error'     => 'sql',
        'msg'       => $e->getMessage()
      ]);
    }
    $result = $statement->fetch();

    if($result['is_user'] === 1) {
      return response()->json([
        'available' => false,
        'error'     => 'user',
        'user'      => $user
      ]);
    } else {
      return response()->json([
        'available' => true,
        'user'      => $user
      ]);
    }
  }


  public function listEmployees (Request $request){
    $data        = json_decode($request->getContent());
    $name        = "%$data->name%";
    $job_type    = $data->job_type;
    $active      = $data->active;
    $page        = $data->page;
    $page_limit  = $data->page_limit;
    $page_offset = 0;
    if($page > 0){ $page_offset = $page * $page_limit; }

    $PDO = DB::getPdo();

    $sql1="SELECT 
      COUNT(*)                AS `count`
    FROM ( SELECT 
        f.id
      FROM pessoas            AS p
      INNER JOIN funcionarios AS f
        ON f.id_pessoa = p.id
      INNER JOIN cargos       AS c
        ON c.id = f.id_cargo
      LEFT JOIN usuarios      AS u
        ON u.id_funcionario = f.id
      LEFT JOIN dependentes   AS d
        ON d.id_funcionario = f.id
      WHERE CONCAT(p.nome, ' ', p.sobrenome) LIKE ?";

    if($active == 0 || $active == 1){
      $sql1 = $sql1."
      AND f.ativo = ?";
    }

    if($job_type){
      $sql1 = $sql1."
      AND c.id = ?";
    }

    $sql1 = $sql1."
      GROUP BY f.id) AS employees;";

    $param = [$name];
    if($active == 0 || $active == 1){ $param[] = $active; }
    if($job_type){ $param[] = $job_type; }

    try {
      $statement = $PDO->prepare($sql1);
      $statement->execute($param);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $result  = $statement->fetchAll();
    $records = $result[0]['count'];
    $count   = $result[0]['count'];
    $pages   = 0;

    if($count > 0){
      while($count){
        if($count < $page_limit){ $pages++; break; }
        $count = $count - $page_limit;
        $pages++;
      }
    }

    if($pages){
      $sql2="SELECT 
        f.id,
        CONCAT(p.nome, ' ', p.sobrenome) AS `name`,
        CASE
          WHEN u.id IS NULL THEN 'false'
          ELSE 'true'
        END                              AS is_user,
        CASE
          WHEN d.id IS NULL THEN 0
          ELSE (SELECT COUNT(id) FROM dependentes WHERE id_funcionario = f.id)
        END                              AS family,
        (SELECT contato AS phone
          FROM  contatos
          WHERE id_tipo = 1
            AND id_pessoa = p.id
          LIMIT 1)                       AS phone,
        (SELECT contato AS email
          FROM  contatos
          WHERE id_tipo = 2
            AND id_pessoa = p.id
          LIMIT 1)                       AS email,
        c.nome                           AS job,
        f.ativo                          AS active
      FROM pessoas                       AS p
      INNER JOIN funcionarios            AS f
        ON f.id_pessoa = p.id
      INNER JOIN cargos                  AS c
        ON c.id = f.id_cargo
      LEFT JOIN usuarios                 AS u
        ON u.id_funcionario = f.id
      LEFT JOIN dependentes              AS d
        ON d.id_funcionario = f.id
      WHERE CONCAT(p.nome, ' ', p.sobrenome) LIKE ?";

      if($active == 0 || $active == 1){
        $sql2 = $sql2."
        AND f.ativo = ?";
      }

      if($job_type){
        $sql2 = $sql2."
        AND c.id = ?";
      }

      $sql2 = $sql2."
        GROUP BY f.id
        ORDER BY p.nome, p.sobrenome
        LIMIT ? OFFSET ?;";

      try {
        $statement = $PDO->prepare($sql2);
        $param[]   = $page_limit;
        $param[]   = $page_offset;
        $statement->execute($param);
      } catch( PDOException $e ){
        return response()->json([
          'success' => false,
          'error'   => 'sql',
          'msg'     => $e->getMessage()
        ]);
      }
      $rows = $statement->fetchAll();

      return response()->json([
        'success'     => true,
        'page'        => $page,
        'page_limit'  => $page_limit,
        'page_offset' => $page_offset,
        'pages'       => $pages,
        'records'     => $records,
        'result'      => $rows
      ]);
    } else {
      return response()->json([
        'success'     => false,
        'error'       => 'empty',
        'page'        => $page,
        'page_limit'  => $page_limit,
        'page_offset' => $page_offset,
        'pages'       => $pages,
        'records'     => $records,
        'result'      => []
      ]);
    }
  }


  public function getEmployee (Request $request){
    $data        = json_decode($request->getContent());
    $id_employee = $data->id_employee;

    $PDO = DB::getPdo();

    // FUNCIONARIO
    $sql_employee = "SELECT
      p.id_entidade    AS id_entity,
      p.id             AS id_person,
      f.id             AS id_employee,
      f.id_cargo       AS id_job_type,
      c.nome           AS job_type,
      a.id             AS id_job_depart,
      a.nome           AS job_depart,
      c.salario        AS salary,
      p.nome           AS `name`,
      p.sobrenome      AS surname,
      EXTRACT(DAY      FROM p.nascimento) AS birth_d,
      EXTRACT(MONTH    FROM p.nascimento) AS birth_m,
      EXTRACT(YEAR     FROM p.nascimento) AS birth_y,
      IFNULL(p.cpf, '')AS cpf,
      f.ativo          AS active
    FROM funcionarios  AS f
    INNER JOIN pessoas AS p
      ON f.id_pessoa = p.id
    INNER JOIN cargos  AS c
      ON f.id_cargo = c.id
    INNER JOIN areas   AS a
      ON c.id_area = a.id
    WHERE f.id = ?;";

    try {
      $statement = $PDO->prepare($sql_employee);
      $statement->execute([$id_employee]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $employee  = $statement->fetch();
    $id_person = $employee['id_person'];

    // DEPENDENTES
    $sql_family = "SELECT
      d.id                       AS id_family,
      d.id_tipo                  AS id_family_type,
      dt.descricao               AS family_type,
      d.id_pessoa                AS id_person,
      p.nome                     AS `name`,
      p.sobrenome                AS surname,
      EXTRACT(DAY                FROM p.nascimento) AS birth_d,
      EXTRACT(MONTH              FROM p.nascimento) AS birth_m,
      EXTRACT(YEAR               FROM p.nascimento) AS birth_y,
      IFNULL(p.cpf, '')          AS cpf
    FROM dependentes             AS d
    INNER JOIN dependentes_tipos AS dt
      ON d.id_tipo = dt.id
    INNER JOIN pessoas           AS p
      ON d.id_pessoa = p.id
    WHERE d.id_funcionario = ?;";

    try {
      $statement = $PDO->prepare($sql_family);
      $statement->execute([$id_employee]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $family = $statement->fetchAll();

    // USUARIO
    $sql_user = "SELECT
      null                     AS `create`,
      u.id_grupo               AS id_user_group,
      g.nome                   AS user_group,
      u.id                     AS id_user,
      u.usuario                AS user,
      null                     AS postfix,
      null                     AS pass
    FROM usuarios              AS u
    INNER JOIN usuarios_grupos AS g
      ON u.id_grupo = g.id
    WHERE u.id_funcionario = ?;";

    try {
      $statement = $PDO->prepare($sql_user);
      $statement->execute([$id_employee]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $user = $statement->fetch();

    // ENDERECO
    $sql_address = "SELECT
      e.id                     AS id_address,
      e.id_tipo                AS id_address_type,
      et.descricao             AS address_type,
      e.logradouro             AS `line`,
      e.numero                 AS `number`,
      IFNULL(e.complemento_numero, '')     AS number_info,
      e.bairro                 AS district,
      e.cidade                 AS city,
      IFNULL(e.estado, '')                 AS `state`,
      e.uf,
      IFNULL(e.cep, '')                    AS cep,
      IFNULL(e.observacao, '')             AS obs
    FROM enderecos             AS e
    INNER JOIN enderecos_tipos AS et
      ON e.id_tipo = et.id
    WHERE e.id_pessoa = ?;";

    try {
      $statement = $PDO->prepare($sql_address);
      $statement->execute([$id_person]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    // $address = $statement->fetchAll();
    $address = $statement->fetch();
    if(!$address){ 
      $address = [
        'id_address'      => '',
        'id_address_type' => '',
        'address_type'    => '',
        'line'            => '',
        'number'          => '',
        'number_info'     => '',
        'district'        => '',
        'city'            => '',
        'state'           => '',
        'cep'             => '',
        'obs'             => ''
      ];
    }

    // TELEFONES
    $sql_phone = "SELECT
      id          AS id_phone,
      contato     AS `phone`,
      IFNULL(observacao, '')  AS obs
    FROM contatos
    WHERE id_tipo = 1 AND id_pessoa = ?;";

    try {
      $statement = $PDO->prepare($sql_phone);
      $statement->execute([$id_person]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $phones = $statement->fetchAll();

    // EMAILS
    $sql_email = "SELECT
      id          AS id_email,
      contato     AS email,
      IFNULL(observacao, '')  AS obs
    FROM contatos
    WHERE id_tipo = 2 AND id_pessoa = ?;";

    try {
      $statement = $PDO->prepare($sql_email);
      $statement->execute([$id_person]);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $emails = $statement->fetchAll();

    return response()->json([
      'success'  => true,
      'employee' => $employee,
      'user'     => $user,
      'family'   => $family,
      'address'  => $address,
      'phones'   => $phones,
      'emails'   => $emails
    ]);
  }


  public function insertEmail (Request $request){
    $data      = json_decode($request->getContent());
    $id_person = $data->id_person;
    $emails    = $data->emails;

    $PDO = DB::getPdo();

    $sql = "INSERT INTO contatos (
      id_tipo,
      id_pessoa,
      contato,
      observacao)
    VALUES( ?, ?, ?, ? );";

    if(sizeof($emails) > 0){
      for($i = 0; $i < sizeof($emails); $i++){
        try {
          $statement = $PDO->prepare($sql); /* INSERT EM CONTATOS SQL */
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

    $PDO = DB::getPdo();

    $sql = "INSERT INTO contatos (
      id_tipo,
      id_pessoa,
      contato,
      observacao)
    VALUES( ?, ?, ?, ? );";

    if(sizeof($phones) > 0){
      for($i = 0; $i < sizeof($phones); $i++){
        try {
          $statement = $PDO->prepare($sql); /* INSERT EM CONTATOS SQL */
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


  public function deleteEmail (Request $request){
    $data     = json_decode($request->getContent());
    $id_email = $data->id_email;

    $PDO = DB::getPdo();

    $sql = "DELETE FROM contatos WHERE id = ?;";

    try {
      $statement = $PDO->prepare($sql); /* DELETE EM CONTATOS SQL */
      $statement->execute([
        $id_email
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


  public function deletePhone (Request $request){
    $data     = json_decode($request->getContent());
    $id_phone = $data->id_phone;

    $PDO = DB::getPdo();

    $sql = "DELETE FROM contatos WHERE id = ?;";

    try {
      $statement = $PDO->prepare($sql); /* DELETE EM CONTATOS SQL */
      $statement->execute([
        $id_phone
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

    $PDO = DB::getPdo();

    $sql = "UPDATE contatos 
    SET 
      contato    = ?,
      observacao = ?
    WHERE id     = ?;";

    try {
      $statement = $PDO->prepare($sql); /* UPDATE EM CONTATOS SQL */
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

    $PDO = DB::getPdo();

    $sql = "UPDATE contatos 
    SET 
      contato    = ?,
      observacao = ?
    WHERE id     = ?;";

    try {
      $statement = $PDO->prepare($sql); /* UPDATE EM CONTATOS SQL */
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

    $PDO = DB::getPdo();

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
          $statement = $PDO->prepare($sql); /* UPDATE EM PESSOAS SQL */
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
          $statement = $PDO->prepare($sql); /* UPDATE EM ENDEREÇOS SQL */
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
          $statement = $PDO->prepare($sql); /* UPDATE EM FUNCIONÁRIOS SQL */
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
            $statement = $PDO->prepare($sql); /* UPDATE EM FUNCIONÁRIOS SQL */
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


  public function listJobs (Request $request){
    $data          = json_decode($request->getContent());
    $name          = "%$data->name%";
    $id_job_depart = $data->id_job_depart;
    $page          = $data->page;
    $page_limit    = $data->page_limit;
    $page_offset   = 0;
    if($page > 0){ $page_offset = $page * $page_limit; }

    $PDO = DB::getPdo();

    $sql1="SELECT 
      COUNT(*) AS `count`
    FROM (
      SELECT id
      FROM   cargos
      WHERE  nome LIKE ?";

    if($id_job_depart){
      $sql1=$sql1."
      AND id_area = ?";
    }

    $sql1=$sql1."
      ) AS jobs;";

    $param = [$name];
    if($id_job_depart){ $param[] = $id_job_depart; }

    try {
      $statement = $PDO->prepare($sql1);
      $statement->execute($param);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $result  = $statement->fetchAll();
    $records = $result[0]['count'];
    $count   = $result[0]['count'];
    $pages   = 0;

    if($count > 0){
      while($count){
        if($count < $page_limit){ $pages++; break; }
        $count = $count - $page_limit;
        $pages++;
      }
    }

    if($pages){
      $sql2="SELECT 
        id         AS id_job_type,
        id_area    AS id_job_depart,
        nome       AS job_type,
        salario    AS salary
      FROM cargos
      WHERE nome LIKE ?";

      if($id_job_depart){
        $sql2=$sql2."
        AND id_area = ?";
      }

      $sql2=$sql2."
        LIMIT ? OFFSET ?;";

      try {
        $statement = $PDO->prepare($sql2);
        $param[]   = $page_limit;
        $param[]   = $page_offset;
        $statement->execute($param);
      } catch( PDOException $e ){
        return response()->json([
          'success' => false,
          'error'   => 'sql',
          'msg'     => $e->getMessage()
        ]);
      }
      $rows = $statement->fetchAll();

      return response()->json([
        'success'     => true,
        'page'        => $page,
        'page_limit'  => $page_limit,
        'page_offset' => $page_offset,
        'pages'       => $pages,
        'records'     => $records,
        'result'      => $rows
      ]);
    } else {
      return response()->json([
        'success'     => false,
        'error'       => 'empty',
        'page'        => $page,
        'page_limit'  => $page_limit,
        'page_offset' => $page_offset,
        'pages'       => $pages,
        'records'     => $records,
        'result'      => []
      ]);
    }
  }


  public function listDeparts (Request $request){
    $data        = json_decode($request->getContent());
    $name        = "%$data->name%";
    $page        = $data->page;
    $page_limit  = $data->page_limit;
    $page_offset = 0;
    if($page > 0){ $page_offset = $page * $page_limit; }

    $PDO = DB::getPdo();

    $sql1="SELECT 
      COUNT(*) AS `count`
    FROM (
      SELECT id
      FROM   areas
      WHERE  nome LIKE ?
    ) AS job_departs;";

    $param = [$name];

    try {
      $statement = $PDO->prepare($sql1);
      $statement->execute($param);
    } catch ( PDOException $e ){
      return response()->json([
        'success' => false,
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ]);
    }
    $result  = $statement->fetchAll();
    $records = $result[0]['count'];
    $count   = $result[0]['count'];
    $pages   = 0;

    if($count > 0){
      while($count){
        if($count < $page_limit){ $pages++; break; }
        $count = $count - $page_limit;
        $pages++;
      }
    }

    if($pages){
      $sql2="SELECT 
        id         AS id_job_depart,
        nome       AS job_depart,
        observacao AS obs
      FROM areas
      WHERE nome LIKE ?
      LIMIT ? OFFSET ?;";

      try {
        $statement = $PDO->prepare($sql2);
        $param[]   = $page_limit;
        $param[]   = $page_offset;
        $statement->execute($param);
      } catch( PDOException $e ){
        return response()->json([
          'success' => false,
          'error'   => 'sql',
          'msg'     => $e->getMessage()
        ]);
      }
      $rows = $statement->fetchAll();

      return response()->json([
        'success'     => true,
        'page'        => $page,
        'page_limit'  => $page_limit,
        'page_offset' => $page_offset,
        'pages'       => $pages,
        'records'     => $records,
        'result'      => $rows
      ]);
    } else {
      return response()->json([
        'success'     => false,
        'error'       => 'empty',
        'page'        => $page,
        'page_limit'  => $page_limit,
        'page_offset' => $page_offset,
        'pages'       => $pages,
        'records'     => $records,
        'result'      => []
      ]);
    }
  }


  public function updateDepart (Request $request){
    $data  = json_decode($request->getContent());
    $depart = $data->depart;

    $PDO = DB::getPdo();

    $sql = "UPDATE areas 
    SET 
      nome       = ?,
      observacao = ?
    WHERE id     = ?;";

    try {
      $statement = $PDO->prepare($sql); /* UPDATE EM AREAS SQL */
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

    $PDO = DB::getPdo();

    $sql = "UPDATE cargos 
    SET 
      id_area    = ?,
      nome       = ?,
      salario    = ?
    WHERE id     = ?;";

    try {
      $statement = $PDO->prepare($sql); /* UPDATE EM CONTATOS SQL */
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