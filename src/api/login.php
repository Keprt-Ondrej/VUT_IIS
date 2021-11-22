<?php
    session_start();

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../app/database.php';

    $database = new database;
    $db = $database->init();
    
    $recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

    $response = array();
    $response["status"] = "OK";

    if(isset($db)){
        $stmt = $db->prepare("SELECT * from users where login=?");
        $stmt->execute(array($recv_data["login"]));
        $row = $stmt->fetch();

        if(!isset($row["login"])){ // user not found
            $response["status"] = "not_user";
        }
        else{
            if($row["password"] == $recv_data["password"]){
                $_SESSION['auth'] = $row["role"];
                $_SESSION['user'] = $row["login"];
            }
            else $response["status"] = "w_pwd"; // passwords don't match
        }
        echo json_encode($response);
    }
?>