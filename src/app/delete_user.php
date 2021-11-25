<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;

  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $response = array();

  if($db != null){

    if(isset($_SESSION['role']) && ($_SESSION['role']  == 'a'  || $_SESSION['role']  == 'm')){

      $stmt = $stmt = $db->prepare("DELETE  from users WHERE login=:login");

      $stmt->execute($recv_data);

      echo json_decode($response);

    }
  }
?>
