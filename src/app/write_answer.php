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

    if(isset($_SESSION['role']) && $_SESSION['role']  == 's' ){

      $stmt = $db->prepare('INSERT INTO answers (login,question_ID,answer)VALUES(:login,:question_ID,:answer)');

      if($stmt->execute($recv_data))
        $response['status'] = 'ok';
      else
        $response['status'] = 'error';

      echo json_decode($response);

    }
  }
?>
