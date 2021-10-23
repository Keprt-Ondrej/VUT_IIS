<?php    
function db_demo(){
    $username = 'xkeprt03';
    $password = 'pu9andoj';
    try {
        $PDO = new PDO("mysql:host=localhost;dbname=xkeprt03;port=/var/run/mysql/mysql.sock", $username, $password);
    } 
    catch (PDOException $e) {
        echo "<script>alert('Nepodařilo se navázat spojení s databází :(')</script>";
        //echo "Connection error: ".$e->getMessage(); //write directly to WEBSITE!!!
	    die();
    }
    $stmt = $PDO->prepare("SELECT * from users");
    $stmt->execute();
    echo "<br><br>Data z DB: <br>";  
    while($row = $stmt->fetch()){
        echo $row["login"] . "\t" . $row["password"] . "<br>";      
    }
}
?>
