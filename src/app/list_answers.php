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

  if(isset($_SESSION['login'])){
    $subject_role = $database->role_in_subject(array("login" => $_SESSION['login'], "question_ID" => $recv_data['question_ID']));
    
    if($subject_role['status'] != 'ok'){
      echo json_encode($subject_role);
      return;
    }
  }
  else $subject_role = array('role'=>null);

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
    $tmp->points = (login_to_points(array("login" => $row['login'], "question_ID" => $recv_data['question_ID'])))->fetchColumn();
    
    array_push($response['answers'], $tmp);
  } 
  echo json_encode($response);

?>
  