<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';
  
  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  if(isset($_SESSION['role']){

    $recv_data['rating_login'] = $_SESSION['login'];

    $number_of_votes = ($database->number_of_ratings(array("login" => $_SESSION['login'], "question_ID" => $recv_data['question_ID']))['statement'])->fetchColumn();

    if($number_of_votes > 3){
      echo json_encode(array("status" => "out_of_votes"));
      return;
    }

    $retval = $database->vote($recv_data);
    
    echo json_encode($retval);
    }
  }
?>

