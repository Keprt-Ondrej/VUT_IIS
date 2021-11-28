<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $retval = $database->ask_question($recv_data);

  if($retval['status'] != 'ok'){
      echo json_encode($retval);
      return;
  }

  $response = array();

  $response['status'] = 'ok';

  $response['question_ID'] = ($retval['statement']->fetch())['question_ID'];

  echo json_encode($response);
?>
  