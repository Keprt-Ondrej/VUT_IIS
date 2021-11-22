<?php
    session_start();
    if(!isset($_SESSION['auth'])) $_SESSION['auth'] = "u";
    if(!isset($_SESSION['user'])) $_SESSION['user'] = "user";
?>


<!DOCTYPE html>
<html>
<head>    
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Fituška v2.0</title>
    <link rel="stylesheet" href="css/link_btn.css">
</head>
      
<body>
    <?php
        echo "<h1>Logged in as ".$_SESSION['user']."</h1>";
    ?>
    <a class="link_btn" href="login.php">Login page</a>
    <!--<div id="cookie_div"></div>
    <script>document.getElementById("cookie_div").innerHTML = document.cookie;</script>-->
    <?php
        $permission = "0";
        $user_type  = "user";
        if(    $_SESSION['auth'] == "a"){
            $permission = "3";
            $user_type  = "admin";
        }
        elseif($_SESSION['auth'] == "t"){
            $permission = "2";
            $user_type  = "teacher";
        }
        elseif($_SESSION['auth'] == "s"){
            $permission = "1";
            $user_type  = "student";
        }
        echo "Permission level ".$permission." aka ".$user_type;
    ?>
</body>
</html>
