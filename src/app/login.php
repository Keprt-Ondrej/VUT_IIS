<?php 

  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  //include_once './database.php';


  //$database = new database();
  //$db = $database->init();
  //$obj = json_decode($_POST, false);
  $data = json_decode(file_get_contents('php://input'), true); 
  echo json_encode($data);
  return;

  

if($db != null){
    
    $stmt = $db->prepare("SELECT * from ");
    $stmt->execute();

    $data = array();
    $data["user"] = "admin";

    return json_encode($data);
    
    while($row = $stmt->fetch()){
      $tmp = new stdClass();
      $tmp->login    = $row["login"];
      $tmp->password = $row["password"];
      array_push($data, $tmp);
    }

      return json_encode($data);
}
    else return json_encode("database init failed");