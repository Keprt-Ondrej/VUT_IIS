<?php 
  session_start();
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new Database();

  $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

  $retval = $database->list_subjects($recv_data);

  if($retval['status'] != 'ok'){
    echo json_encode($retval);
    return;
  }

  $s_IDs = array();
  $s_approved = array();
  if(isset($_SESSION['login'])){
    $user_subjects = $database->list_subjects($recv_data, "study");
    if($user_subjects['status'] == 'ok'){
      while($row = $user_subjects['statement']->fetch()){
        if($row['login'] == $_SESSION['login']){
          array_push($s_IDs, $row["subject_ID"]);
          $s_approved[$row["subject_ID"]] = $row["approved"];
        }
      }
    }
  }

  $response = array();

  $response['status'] = 'ok';

  $response['subjects'] = array();

  while($row = $retval['statement']->fetch()){
    $tmp = new stdClass();
    $tmp->login        = $row["login"];
    $tmp->subject_ID   = $row["subject_ID"];
    $tmp->subject_name = $row["subject_name"];
    $tmp->approved     = $row["approved"];
    if(isset($_SESSION['login'])){
      if($row["login"] == $_SESSION['login']){
        if($row["approved"] == null) $tmp->role = "Nerozhodnuto";
        else if($row["approved"])    $tmp->role = "Učím";
        else if(!$row["approved"])   $tmp->role = "Zamítnut";
      }
      else{
        if(in_array($row["subject_ID"], $s_IDs)){
          if($s_approved[$row["subject_ID"]] == null) $tmp->role = "Nerozhodnuto";
          else if($s_approved[$row["subject_ID"]])    $tmp->role = "Přihlášen";
          else if(!$s_approved[$row["subject_ID"]])   $tmp->role = "Zamítnut";
        }
        else $tmp->role = "0";
      }
    }
    else $tmp->role = "0";
    array_push($response['subjects'], $tmp);
  } 
  echo json_encode($response);

?>
  