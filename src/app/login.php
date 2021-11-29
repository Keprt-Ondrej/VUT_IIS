<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database;
  
  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $response = array();
  
  $_SESSION["pocet"] = 0; 

  $retval = $database->find_user($recv_data);

  if($retval['status'] != 'ok'){
    echo json_encode($retval);
    return;
  }

  $response['status'] = "ok";
  
  $row = $retval['statement']->fetch();

  if(!isset($row["login"])){ // user not found
    $response["role"] = "not_user";
    echo json_encode($response);
    return;
  }

  if($row["deleted"]){
    $response["role"] = "deleted_user";
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
      $response["role"] = "w_pwd"; // passwords don't matchs 
      echo json_encode($response);  
    } 
  
?>
