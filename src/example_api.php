<?php
    require "api_func.php";
    session_start();
    header('Content-Type: application/json;');
    
    if($_SERVER["REQUEST_METHOD"] == "POST") 
        echo example_json($_POST["stuff"]);
    else
        echo example_json("users");
?>