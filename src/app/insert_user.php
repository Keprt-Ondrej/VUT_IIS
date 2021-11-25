<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;

  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $response = array();

  //$response["session"] = session_id(); 

  if($db != null){

    if(isset($_SESSION['role']) && $_SESSION['role']  == 'a'){

      $stmt = $db->prepare('INSERT INTO users (login,password,role)VALUES(?,?,?)');

      if($stmt->execute(array($recv_data["login"],$recv_data["pwd"],$recv_data["role"])))    //$recv_data
        $response['status'] = 'ok';
      else
        $response['status'] = 'login_used';

      echo json_encode($response);
    }
  }
?>
