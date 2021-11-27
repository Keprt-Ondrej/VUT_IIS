<?php
session_start();
header('Acces-Control-Allow-Origin: *');
header('Content-Type: application/json');

$recv_data = json_decode(file_get_contents('php://input'), true); // POST Data

$response = array();

if(isset($_SESSION["role"]) && isset($_SESSION["login"])){
    $response["role"] = $_SESSION["role"];
    $response["login"] = $_SESSION["login"];
}
else{
    $response["role"] = null;
    $response["login"] = null;
}
    

echo json_encode($response);
return;