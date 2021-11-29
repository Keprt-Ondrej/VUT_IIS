<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

    if(isset($_SESSION['role'])){
      $recv_data['login'] = $_SESSION['login'];
      $retval = $database->sign_up_as_student($recv_data);
      echo json_encode($retval);
    }
    else{
      echo json_encode(array("status" => "not_logged")); 
    }       
?>
  