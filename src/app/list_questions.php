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

  if(isset($_SESSION['login'])){
    $subject_role = $database->role_in_subject(array("login" => $_SESSION['login'], "category_ID" => $recv_data['category_ID']));
    
    if($subject_role['status'] != 'ok'){
      echo json_encode($subject_role);
      return;
    }
  }
  else $subject_role = array('role'=>null);

  $response = array();

  $response['status'] = 'ok';

  $response['brief'] = ($retval['statement_parent']->fetch())['brief'];

  if(isset($subject_role["approved"]) && $subject_role["approved"]) $response['role'] = $subject_role['role'];
  else                                                              $response['role'] = null;

  $response['questions'] = array();
 
  while($row = $retval['statement_children']->fetch()){
    $tmp = new stdClass();
    $tmp->brief  = $row["brief"];
    $tmp->question_ID = $row["question_ID"];
    array_push($response['questions'], $tmp);
  } 
  echo json_encode($response);

?>
  