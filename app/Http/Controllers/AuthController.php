<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $data = json_decode($request->getContent());
    $user = $data->user;
    $pass = $data->pass;

    $PDO = DB::getPdo();

    // $credentials = $request->validate([
    //   'email' => ['required', 'email'],
    //   'password' => ['required'],
    // ]);

    if (Auth::attempt(['email'=>$user, 'password'=>$pass])) {
      $request->session()->regenerate();
      $sql3 = "SELECT
      u.id                       AS id_user,
      u.usuario                  AS user,
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
      WHERE u.usuario = ?;";
      try {
        $statement3 = $PDO->prepare($sql3);
        $statement3->execute([$user]);
      } catch ( PDOException $e ){
        return response()->json([
            'session_started' => false,
            'error'           => 'sql',
            'msg'             => $e->getMessage()
          ]);
      }
      $result3 = $statement3->fetch();

      $request->session()->put('authenticated', true);
      $request->session()->put('id_user',       $result3['id_user']);
      $request->session()->put('user',          $result3['user']);
      $request->session()->put('id_employee',   $result3['id_employee']);
      $request->session()->put('id_group',      $result3['id_group']);
      $request->session()->put('group',         $result3['group']);
      $request->session()->put('id_person',     $result3['id_person']);
      $request->session()->put('name_user',     $result3['name_user']);
      $request->session()->put('surname_user',  $result3['surname_user']);
      $request->session()->put('id_entity',     $result3['id_entity']);
      $request->session()->put('entity',        $result3['entity']);
      $request->session()->put('postfix',       $result3['postfix']);

      return response()->json([
          'session_started' => true
        ]);
    }

    return response()->json([
        'session_started' => false,
        'error' => 'no match or inactive'
      ]);

    // return back()->withErrors([
    //   'email' => 'The provided credentials do not match our records.',
    // ])->onlyInput('email');
  }


  public function logout(Request $request){
    $data = json_decode($request->getContent());
    $id_user = $data->id_user;

    if(session()->has('id_user') && session('id_user') == $id_user)
    {
      Auth::guard('web')->logout();
      $request->session()->invalidate();
      return response()->json([
        'session_ended' => true
      ]);
    }
    else
    {
      return response()->json([
        'session_ended' => false
      ]);
    }
  }
}