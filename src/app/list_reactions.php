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

  if(isset($_SESSION['login'])){
    $subject_role = $database->role_in_subject(array("login" => $_SESSION['login'], "question_ID" => $recv_data['question_ID']));
    
    if($subject_role['status'] != 'ok'){
      echo json_encode($subject_role);
      return;
    }
  }
  else $subject_role = array('role'=>null, 'approved' => null);

  $response = array();

  $response['status'] = 'ok';

  $response['answer'] = ($retval['statement_parent']->fetch())['answer'];
  
  if($subject_role["approved"]) $response['role'] = $subject_role['role'];
  else                          $response['role'] = null;

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
  