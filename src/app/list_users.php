<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  if(isset($_SESSION['role']) && $_SESSION['role']  == 'a'){

    $retval = $database->list_users($recv_data);

    if($retval['status'] != 'ok'){
      echo json_encode($retval);
      return;
    }

    $response = array();

    $response['status'] = 'ok';

    $response['users'] = array();

    while($row = $retval['statement']->fetch()){
      $tmp = new stdClass();
      $tmp->login  = $row["login"];
      $tmp->role = $row["role"];
      array_push($response['users'], $tmp);
    } 
      echo json_encode($response);
  }
  
?>
  