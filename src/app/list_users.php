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

    if(isset($_SESSION['role']) && $_SESSION['role']  == 's' ){

      $stmt = $stmt = $db->prepare("SELECT login,role from users");

      $stmt->execute();

      while($row = $stmt->fetch()){
            $tmp = new stdClass();
            $tmp->login    = $row["login"];
            $tmp->password = $row["role"];
            array_push($response, $tmp);
        } 

      echo json_decode($response);

    }
  }
?>
