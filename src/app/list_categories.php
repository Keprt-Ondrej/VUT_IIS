<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $retval = $database->list_categories($recv_data);

  if($retval['status'] != 'ok'){
    echo json_encode($retval);
    return;
  }

  $response = array();

  $response['status'] = 'ok';

  $response['subject_name'] = ($retval['statement_parent']->fetch())['subject_name'];

  $response['categories'] = array();

  while($row = $retval['statement_children']->fetch()){
    $tmp = new stdClass();
    $tmp->brief  = $row["brief"];
    $tmp->category_ID = $row["category_ID"];
    array_push($response['categories'], $tmp);
  } 
  echo json_encode($response);

?>
  