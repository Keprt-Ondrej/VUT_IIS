<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;

  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $response = array();

  $response["session"] = session_id();

  if($db != null){

    if(isset($_SESSION['role']) && $_SESSION['role']  == 't'){

      $stmt = $db->prepare('UPDATE questions SET answer=:answer WHERE question_ID=:question_ID, category_ID =:category_ID ');

      if($stmt->execute($recv_data))
        $response['status'] = 'ok';
      else
        $response['status'] = 'internal_error';

      echo json_decode($response);

    }
  }
?>
