<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class DeleteController extends Controller
{
  protected $PDO;


  public function __construct()
  {
    $this->PDO = DB::getPdo();
    $this->PDO->setAttribute($this->PDO::ATTR_ERRMODE,            $this->PDO::ERRMODE_EXCEPTION);
    $this->PDO->setAttribute($this->PDO::ATTR_DEFAULT_FETCH_MODE, $this->PDO::FETCH_ASSOC);
    $this->PDO->setAttribute($this->PDO::ATTR_EMULATE_PREPARES,   false);
  }


  public function deleteFamily (Request $request){
    $data      = json_decode($request->getContent());
    $id_family = $data->id_family;
    $id_person = $data->id_person;

    $sql1 = "DELETE FROM dependentes WHERE id = ?;";

    try {
      $statement = $this->PDO->prepare($sql1); /* DELETE EM DEPENDENTES SQL1 */
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
      $statement = $this->PDO->prepare($sql2); /* DELETE EM PESSOAS SQL2 */
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


  public function deleteEmail (Request $request){
    $data     = json_decode($request->getContent());
    $id_email = $data->id_email;

    $sql = "DELETE FROM contatos WHERE id = ?;";

    try {
      $statement = $this->PDO->prepare($sql); /* DELETE EM CONTATOS SQL */
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

    $sql = "DELETE FROM contatos WHERE id = ?;";

    try {
      $statement = $this->PDO->prepare($sql); /* DELETE EM CONTATOS SQL */
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
}