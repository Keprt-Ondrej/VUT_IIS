<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $retval = $database->list_answers($recv_data);

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
  
  $response['role'] = $subject_role['role'];

  $row = $retval['statement_parent']->fetch();

  $response['brief'] = $row['brief'];
  $response['full_question'] = $row['full_question'];
  $response['answer'] = $row['answer'];

  $response['answers'] = array();


  while($row = $retval['statement_children']->fetch()){
    $tmp = new stdClass();
    $tmp->login  = $row['login'];
    $tmp->answer = $row['answer'];
    array_push($response['answers'], $tmp);
  } 
  echo json_encode($response);

?>
  