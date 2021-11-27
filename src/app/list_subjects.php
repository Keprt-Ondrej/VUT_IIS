<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $retval = $database->list_subjects($recv_data);

  if($retval['status'] != 'ok'){
    echo json_encode($retval);
    return;
  }

  $response = array();

  $response['status'] = 'ok';

  $response['subjects'] = array();

  while($row = $retval['statement']->fetch()){
    $tmp = new stdClass();
    $tmp->login  = $row["login"];
    $tmp->subject_ID = $row["subject_ID"];
    array_push($response['subjects'], $tmp);
  } 
  echo json_encode($response);

?>
  