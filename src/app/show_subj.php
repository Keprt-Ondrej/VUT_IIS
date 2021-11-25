<?php
  function prepare($where,$response){

    $stmt = $stmt = $db->prepare("SELECT login,subject_ID from teach".$where);
    $stmt->execute();

    while($row = $stmt->fetch()){
      $tmp = new stdClass();
      $tmp->login    = $row["login"];
      $tmp->subject_ID = $row["subject_ID"];
      array_push($response, $tmp);
    } 

    
  }
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;

  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $response = array();

  if($db != null){

    if(isset($_SESSION['role']) && ($_SESSION['role']  == 'a'  || $_SESSION['role']  == 'm' )){

      if($recv_data['all']){

        prepare("",$response);
        return;
      }
      if($recv_data['uapproved']){

        prepare("WHERE approveved=false",$response);
      }
      if($recv_data['approved']){

        prepare("WHERE approveved=true",$response);
      }

      echo json_encode($response);

    }
  }
?>
