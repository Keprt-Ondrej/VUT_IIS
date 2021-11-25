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

    if(isset($_SESSION['role']) && $_SESSION['role']  == 't' || $_SESSION['role']  == 's' ){

      $stmt = $db->prepare('INSERT INTO questions (category_ID,brief,full_question)VALUES(:category_ID,:brief,:full_question)');

      if($stmt->execute($recv_data))
        $response['status'] = 'ok';
      else
        $response['status'] = 'internal_error';

      echo json_encode($response);

    }
  }
?>
