<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$recv_data = json_decode(file_get_contents('php://input'), true); // POST Data
$response = array();
$_SESSION["pocet"] += 1;

$response["pocet"] = $_SESSION["pocet"];
echo json_encode($response);
return;
?>