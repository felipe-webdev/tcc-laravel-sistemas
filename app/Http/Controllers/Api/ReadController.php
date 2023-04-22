<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class ReadController extends Controller
{
  protected $PDO;


  public function __construct()
  {
    $this->PDO = DB::getPdo();
    $this->PDO->setAttribute($this->PDO::ATTR_ERRMODE,            $this->PDO::ERRMODE_EXCEPTION);
    $this->PDO->setAttribute($this->PDO::ATTR_DEFAULT_FETCH_MODE, $this->PDO::FETCH_ASSOC);
    $this->PDO->setAttribute($this->PDO::ATTR_EMULATE_PREPARES,   false);
  }

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


  public function getSystemTypes (Request $request){
    $result = $this->PDO->query("SELECT
      descricao AS label,
      id        AS `value`
    FROM pessoas_tipos ORDER BY id;");
    $person = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      descricao AS label,
      id        AS `value`
    FROM dependentes_tipos ORDER BY id;");
    $family = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      descricao AS label,
      id        AS `value`
    FROM enderecos_tipos ORDER BY id;");
    $address = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      descricao AS label,
      id        AS `value`
    FROM contatos_tipos ORDER BY id;");
    $contact = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      nome AS label,
      id   AS `value`
    FROM usuarios_grupos ORDER BY id;");
    $user_group = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      nome AS label,
      id   AS `value`
    FROM cargos ORDER BY id;");
    $job = $result->fetchAll();

    $result = $this->PDO->query("SELECT
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
    $result = $this->PDO->query("SELECT
      COUNT(*)              AS total
    FROM pessoas            AS p
    INNER JOIN funcionarios AS f
      ON f.id_pessoa = p.id;");
    $total = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      COUNT(*)              AS total
    FROM pessoas            AS p
    INNER JOIN funcionarios AS f
      ON f.id_pessoa = p.id
    WHERE f.ativo = 1;");
    $active = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      COUNT(*)              AS total
    FROM pessoas            AS p
    INNER JOIN funcionarios AS f
      ON f.id_pessoa = p.id
    WHERE f.ativo = 0;");
    $inactive = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      COUNT(*)              AS total
    FROM dependentes        AS d
    INNER JOIN funcionarios AS f
      ON f.id = d.id_funcionario;");
    $total_family = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      COUNT(*)              AS total
    FROM dependentes        AS d
    INNER JOIN funcionarios AS f
      ON f.id = d.id_funcionario
    WHERE f.ativo = 1;");
    $active_family = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      COUNT(*)              AS total
    FROM dependentes        AS d
    INNER JOIN funcionarios AS f
      ON f.id = d.id_funcionario
    WHERE f.ativo = 0;");
    $inactive_family = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      COUNT(*)              AS total
    FROM usuarios           AS u
    INNER JOIN funcionarios AS f
      ON f.id = u.id_funcionario;");
    $total_user = $result->fetchAll();

    $result = $this->PDO->query("SELECT
      COUNT(*)              AS total
    FROM usuarios           AS u
    INNER JOIN funcionarios AS f
      ON f.id = u.id_funcionario
    WHERE f.ativo = 1;");
    $active_user = $result->fetchAll();

    $result = $this->PDO->query("SELECT
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


  public function isUserAvailable (Request $request){
    $data = json_decode($request->getContent());
    $user = $data->user;

    $sql = "SELECT COUNT(id) AS is_user FROM usuarios WHERE usuario = ?;";
    try {
      $statement = $this->PDO->prepare($sql);
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
      $statement = $this->PDO->prepare($sql1);
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
        $statement = $this->PDO->prepare($sql2);
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
      $statement = $this->PDO->prepare($sql_employee);
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
      $statement = $this->PDO->prepare($sql_family);
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
      $statement = $this->PDO->prepare($sql_user);
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
      e.id                             AS id_address,
      e.id_tipo                        AS id_address_type,
      et.descricao                     AS address_type,
      e.logradouro                     AS `line`,
      e.numero                         AS `number`,
      IFNULL(e.complemento_numero, '') AS number_info,
      e.bairro                         AS district,
      e.cidade                         AS city,
      IFNULL(e.estado, '')             AS `state`,
      e.uf,
      IFNULL(e.cep, '')                AS cep,
      IFNULL(e.observacao, '')         AS obs
    FROM enderecos             AS e
    INNER JOIN enderecos_tipos AS et
      ON e.id_tipo = et.id
    WHERE e.id_pessoa = ?;";

    try {
      $statement = $this->PDO->prepare($sql_address);
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
      $statement = $this->PDO->prepare($sql_phone);
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
      $statement = $this->PDO->prepare($sql_email);
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


  public function listJobs (Request $request){
    $data          = json_decode($request->getContent());
    $name          = "%$data->name%";
    $id_job_depart = $data->id_job_depart;
    $page          = $data->page;
    $page_limit    = $data->page_limit;
    $page_offset   = 0;
    if($page > 0){ $page_offset = $page * $page_limit; }

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
      $statement = $this->PDO->prepare($sql1);
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
        $statement = $this->PDO->prepare($sql2);
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

    $sql1="SELECT
      COUNT(*) AS `count`
    FROM (
      SELECT id
      FROM   areas
      WHERE  nome LIKE ?
    ) AS job_departs;";

    $param = [$name];

    try {
      $statement = $this->PDO->prepare($sql1);
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
        $statement = $this->PDO->prepare($sql2);
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


  public function getImage(Request $request){
    $id_person = $request->input('id_person');

    $sql = "SELECT
      imagem
    FROM pessoas
    WHERE id = ?;";

    try {
      $statement = $this->PDO->prepare($sql);
      $statement->execute([$id_person]);
    } catch ( PDOException $e ){
      return response()->json([
        'error'   => 'sql',
        'msg'     => $e->getMessage()
      ], 200, [
        'success' => 'false'
      ]);
    }
    $result = $statement->fetch();

    if (!$result['imagem']) {
      return response()->json([
        'error'   => 'no image'
      ], 200, [
        'success' => 'false'
      ]);
    }

    $headers = [
      'success'        => 'true',
      'Content-Type'   => 'image/png',
      'Content-Length' => strlen($result['imagem'])
    ];

    return response($result['imagem'], 200, $headers);
  }
}