<?php 

  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once 'database.php';

  $database = new database;
  $db = $database->init();
  
  $recv_data = json_decode(file_get_contents('php://input'), true); 

  $response = array();
  if($db != null){    
    $stmt = $db->prepare("SELECT * from users where login=?");
    $stmt->execute(array($recv_data["login"]));
    $row = $stmt->fetch();
    if(isset($row["role"])){
      if($row["password"] == $recv_data["password"]){
        $response["user"] = $row["role"];
        echo json_encode($response);
        return;
      }
      else{
        $response["user"]= "w_pwd";
        echo json_encode($response);
        return;
      }      
    }
    else{
      $response["user"]= "not_user";
      echo json_encode($response);
      return;
    }
    
return;

?>
