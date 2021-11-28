<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $retval = $database->list_students_to_subjects($recv_data);

  if($retval['status'] != 'ok'){
    echo json_encode($retval);
    return;
  }

  $subject_role = $database->role_in_subject(array("login" => $_SESSION['login'], "subject_ID" => $recv_data['subject_ID']));
  
  if($subject_role['status'] != 'ok'){
    echo json_encode($subject_role);
    return;
  }

  $response = array();

  $response['status'] = 'ok';

  $response['students'] = array();

  while($row = $retval['statement']->fetch()){
    $tmp = new stdClass();
    $tmp->login  = $row["login"];
    $tmp->approved = $row["approved"];
    array_push($response['students'], $tmp);
  } 
  echo json_encode($response);

?>
  