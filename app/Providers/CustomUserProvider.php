<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\DB;

class CustomUserProvider implements UserProvider
{
  protected $PDO;


  public function __construct()
  {
    $this->PDO = DB::connection()->getPdo();
  }


  public function retrieveById($identifier)
  {
    $stmt = $this->PDO->prepare(
      "SELECT 
      u.id                       AS id_user,
      u.usuario                  AS user,
      u.senha                    AS pass,
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
        ON e.id = p.id_entidade
      WHERE u.id = ?;"
    );
    $stmt->execute([$identifier]);
    $result = $stmt->fetch();
    if ($result)
    {
      return new GenericUser([
        'id'           => $result['id_user'],
        'name'         => $result['name_user'],
        'email'        => $result['user'],
        'password'     => $result['pass'],
        'id_user'      => $result['id_user'],
        'user'         => $result['user'],
        'id_employee'  => $result['id_employee'],
        'id_group'     => $result['id_group'],
        'group'        => $result['group'],
        'id_person'    => $result['id_person'],
        'name_user'    => $result['name_user'],
        'surname_user' => $result['surname_user'],
        'id_entity'    => $result['id_entity'],
        'entity'       => $result['entity'],
        'postfix'      => $result['postfix'],
      ]);
    }
    else 
    {
      return null;
    }
  }


  public function retrieveByCredentials(array $credentials)
  {
    $stmt = $this->PDO->prepare(
      "SELECT 
      u.id                       AS id_user,
      u.usuario                  AS user,
      u.senha                    AS pass,
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
        ON e.id = p.id_entidade
      WHERE u.usuario = ?
        AND f.ativo = ?;"
    );
    $stmt->execute([$credentials['email'], 1]);
    $result = $stmt->fetch();
    if ($result)
    {
      return new GenericUser([
        'id'           => $result['id_user'],
        'name'         => $result['name_user'],
        'email'        => $result['user'],
        'password'     => $result['pass'],
        'id_user'      => $result['id_user'],
        'user'         => $result['user'],
        'id_employee'  => $result['id_employee'],
        'id_group'     => $result['id_group'],
        'group'        => $result['group'],
        'id_person'    => $result['id_person'],
        'name_user'    => $result['name_user'],
        'surname_user' => $result['surname_user'],
        'id_entity'    => $result['id_entity'],
        'entity'       => $result['entity'],
        'postfix'      => $result['postfix'],
      ]);
    }
    else 
    {
      return null;
    }
  }


  public function validateCredentials(Authenticatable $user, array $credentials)
  {
    return password_verify($credentials['password'], $user->getAuthPassword());
    // return app('hash')->check($credentials['password'], $user->getAuthPassword());
  }


  public function retrieveByToken($identifier, $token)
  {
    $stmt = $this->PDO->prepare('SELECT * FROM usuarios WHERE id = ? AND remember_token = ?');
    $stmt->execute([$identifier, $token]);
    return $stmt->fetch();
  }


  public function updateRememberToken(Authenticatable $user, $token)
  {
    $stmt = $this->PDO->prepare('UPDATE usuarios SET remember_token = ? WHERE id = ?');
    $stmt->execute([$token, $user->getAuthIdentifier()]);
  }
}
