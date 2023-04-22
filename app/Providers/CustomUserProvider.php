<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\DB;

class CustomUserProvider implements UserProvider
{ // USER PROVIDER CUSTOMIZADO PARA SPA
  protected $PDO;


  protected $SQL = "SELECT 
  u.id                       AS id_user,
  u.usuario                  AS user,
  u.senha                    AS pass,
  u.remember_token           AS remember_token,
  u.id_funcionario           AS id_employee,
  u.id_grupo                 AS id_group,
  g.nome                     AS `group`,
  p.id                       AS id_person,
  p.nome                     AS name_user,
  p.sobrenome                AS surname_user,
  e.id                       AS id_entity,
  e.nome                     AS entity,
  e.posfixo_usuario          AS postfix
  FROM usuarios              AS u
  INNER JOIN usuarios_grupos AS g
    ON g.id = u.id_grupo
  INNER JOIN funcionarios    AS f
    ON f.id = u.id_funcionario
  INNER JOIN pessoas         AS p
    ON p.id = f.id_pessoa
  INNER JOIN entidades       AS e
    ON e.id = p.id_entidade";


  public function __construct()
  {
    $this->PDO = DB::connection()->getPdo();
  }


  public function retrieveById($identifier)
  {
    $WHERE = " WHERE u.id = ?;";
    $stmt = $this->PDO->prepare($this->SQL.$WHERE);
    $stmt->execute([$identifier]);
    $result = $stmt->fetch();
    return $this->testResult($result);
  }


  public function retrieveByCredentials(array $credentials)
  {
    $WHERE = " WHERE u.usuario = ? AND f.ativo = ?;";
    $stmt = $this->PDO->prepare($this->SQL.$WHERE);
    $stmt->execute([$credentials['email'], 1]);
    $result = $stmt->fetch();
    return $this->testResult($result);
  }


  public function validateCredentials(Authenticatable $user, array $credentials)
  {
    return password_verify($credentials['password'], $user->getAuthPassword());
    // return app('hash')->check($credentials['password'], $user->getAuthPassword());
  }


  public function retrieveByToken($identifier, $token)
  {
    $WHERE = " WHERE id = ? AND remember_token = ?;";
    $stmt = $this->PDO->prepare($this->SQL.$WHERE);
    $stmt->execute([$identifier, $token]);
    $result = $stmt->fetch();
    return $this->testResult($result);
  }


  public function updateRememberToken(Authenticatable $user, $token)
  {
    $stmt = $this->PDO->prepare('UPDATE usuarios SET remember_token = ? WHERE id = ?');
    $stmt->execute([$token, $user->getAuthIdentifier()]);
  }


  private function testResult($result){
    if ($result)
    {
      return new GenericUser([
        'id'             => $result['id_user'],
        'name'           => $result['name_user'],
        'email'          => $result['user'],
        'password'       => $result['pass'],
        'remember_token' => $result['remember_token'],
        'id_user'        => $result['id_user'],
        'user'           => $result['user'],
        'id_employee'    => $result['id_employee'],
        'id_group'       => $result['id_group'],
        'group'          => $result['group'],
        'id_person'      => $result['id_person'],
        'name_user'      => $result['name_user'],
        'surname_user'   => $result['surname_user'],
        'id_entity'      => $result['id_entity'],
        'entity'         => $result['entity'],
        'postfix'        => $result['postfix'],
      ]);
    }
    else 
    {
      return null;
    }
  }
}
