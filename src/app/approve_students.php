<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

    if(isset($_SESSION['role']) && $_SESSION['role']  != 's'){

      $retval = $database->approve_student($recv_data);

       if($retval['status'] != 'ok'){
        echo json_encode($retval);
        return;
      } 
 
      echo json_encode(array_merge($retval,$recv_data));
    }
?>
  