<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;

  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $response = array();

  $response["session"] = session_id();

  if($db != null){

    if(isset($_SESSION['role']) && $_SESSION['role']  == 't'){ // only teacher can approve studnets

      $stmt = $db->prepare("SELECT role from users where login=?"); 
      $stmt->execute($recv_data["login"]);
      $row = $stmt->fetch();

      if($row['role'] != 's'){
        $response['status'] = 'error';
        return;
      }

      $stmt = $db->prepare('UPDATE study SET approveved=:approveved WHERE login=:login ,subject_ID=:subject_ID');

      if($stmt->execute($recv_data))
        $response['status'] = 'ok';
      else
        $response['status'] = 'error';

      echo json_decode($response);

    }
  }
?>
