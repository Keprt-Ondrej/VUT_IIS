<?php
$db_user  = 'xkeprt03';
$db_pass  = 'otoj5epu';
$err      = new stdClass();
try {
    $database = new PDO("mysql:host=localhost;dbname=".$db_user.";port=/var/run/mysql/mysql.sock", $db_user, $db_pass);
}
catch (PDOException $e) {
    $err->error = "Database connection failed.";
}
function example_json($something){
    global $database, $err;
    if(isset($database)){
        $stmt = $database->prepare("SELECT * from ".$something);
        $stmt->execute();
        $data = array();

        while($row = $stmt->fetch()){
            $tmp = new stdClass();
            $tmp->login    = $row["login"];
            $tmp->password = $row["password"];
            array_push($data, $tmp);
        }

        return json_encode($data);
    }
    else return json_encode($err);
}


?>