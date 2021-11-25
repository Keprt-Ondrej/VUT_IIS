<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;

  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $response = array();

  $recv_data["reaction_login"] = $_SESSION["login"];

  $response["session"] = session_id();

  if($db != null){

    if(isset($_SESSION['role']) && $_SESSION['role']  == 's' ){

      $stmt = $db->prepare('INSERT INTO reactions (answer_login,reaction_login,question_ID,reaction)VALUES(:answer_login,:reaction_login,:question_ID,:reaction)');

      if($stmt->execute($recv_data))
        $response['status'] = 'ok';
      else
        $response['status'] = 'internal_error';

      echo json_decode($response);

    }
  }
?>
