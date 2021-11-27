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

  if(isset($_SESSION['login'])){
    $subject_role = $database->role_in_subject(array("login" => $_SESSION['login'], "subject_ID" => $recv_data['subject_ID']));
    
    if($subject_role['status'] != 'ok'){
      echo json_encode($subject_role);
      return;
    }
  }
  else{
    $subject_role = array('status' => 'ok', 'role' => null);
  }

  $response = array();
  
  $response['status'] = 'ok';
  
  $response['subject_name'] = ($retval['statement_parent']->fetch())['subject_name'];

  $response['role'] = $subject_role['role'];
  
  $response['categories'] = array();

  while($row = $retval['statement_children']->fetch()){
    $tmp = new stdClass();
    $tmp->brief  = $row["brief"];
    $tmp->category_ID = $row["category_ID"];
    array_push($response['categories'], $tmp);
  } 
  echo json_encode($response);

?>
  