<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $recv_data['login'] = $_SESSION['login'];

    if(isset($_SESSION['role'])){
      $retval = $database->create_course($recv_data);
      echo json_encode($retval);
    }
?>
  