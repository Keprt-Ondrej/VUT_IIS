<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $retval = $database->list_reactions($recv_data);

  if($retval['status'] != 'ok'){
    echo json_encode($retval);
    return;
  }

  $response = array();

  $response['status'] = 'ok';

  $response['answer'] = ($retval['statement_parent']->fetch())['answer'];

  while($row = $retval['statement_children']->fetch()){
    $tmp = new stdClass();
    $tmp->reaction_ID  = $row["reaction_ID"];
    $tmp->reaction_login = $row["reaction_login"];
    $tmp->text = $row["text"];
    array_push($response, $tmp);
  } 
  echo json_encode($response);

?>
  