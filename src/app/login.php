<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;

  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $response = array();
  
  $_SESSION["pocet"] = 0; 

  if($db != null){

    $stmt = $db->prepare("SELECT * from users where login=?");  
    $stmt->execute(array($recv_data["login"]));
    $row = $stmt->fetch();

    if(!isset($row["login"])){ // user not found
      $response["user"] = "not_user";
      echo json_encode($response);
      return;
    }

    if($row["password"] == $recv_data["password"]){ 
      $response["role"] = $row["role"];
      $_SESSION["role"] = $row["role"];
      $response["login"] = $row["login"];      
      $_SESSION["login"] = $row["login"];

      echo json_encode($response);
    }
    else{
      $response["user"] = "w_pwd"; // passwords don't matchs 
      echo json_encode($response);  
    } 
  }
?>
