<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $retval = $database->list_questions($recv_data);

  if($retval['status'] != 'ok'){
    echo json_encode($retval);
    return;
  }

  $response = array();

  $response['status'] = 'ok';

  $response['brief'] = ($retval['statement_parent']->fetch())['brief'];

  $response['questions'] = array();
 
  while($row = $retval['statement_children']->fetch()){
    $tmp = new stdClass();
    $tmp->brief  = $row["brief"];
    $tmp->question_ID = $row["question_ID"];
    array_push($response['questions'], $tmp);
  } 
  echo json_encode($response);

?>
  