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

  $subject_role = $database->role_in_subject(array("login" => $_SESSION['login'], "question_ID" => $recv_data['question_ID']));
  
  if($subject_role['status'] != 'ok'){
    echo json_encode($subject_role);
    return;
  }

  $response = array();

  $response['status'] = 'ok';

  $response['answer'] = ($retval['statement_parent']->fetch())['answer'];
  
  $response['role'] = $subject_role['role'];

  $response['reactions'] = array();

  while($row = $retval['statement_children']->fetch()){
    $tmp = new stdClass();
    $tmp->reaction_ID  = $row["reaction_ID"];
    $tmp->reaction_login = $row["reaction_login"];
    $tmp->text = $row["text"];
    array_push($response['reactions'], $tmp);
  } 
  echo json_encode($response);

?>
  