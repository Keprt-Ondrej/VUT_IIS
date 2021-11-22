<?php 

  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;
  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); 

  if($db != null){

    //echo  $recv_data["login"];
    
    $stmt = $db->prepare("SELECT * from users where login=". $recv_data["login"]);
    $stmt->execute();

    $response = array();

    while($row = $stmt->fetch()){
            $tmp = new stdClass();
            $tmp->login    = $row["login"];
            $tmp->password = $row["password"];
            array_push($response, $tmp);
        }

    echo json_encode($row);
    return;

    if(isset($row["login"]) && isset($row["password"])){
        
        $tmp->login = $row["login"];
        $tmp->password = $row["password"];
    }
    else{
      $response["user"] = "not";
      echo json_encode($response);
    }

    if ($tmp->password == recv_data["password"]){
      $response["user"] = $tmp->role;
      echo json_encode($response);
    }
    else{
      $response["user"] = "pwd";
      echo json_encode($response);
    }
  }
