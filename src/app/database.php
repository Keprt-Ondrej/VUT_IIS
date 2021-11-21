<?php 
  class Database {
    $db_user  = 'xkeprt03';
    $db_pass  = 'otoj5epu';
    $database;


    public function init() {
      try {
        $database = new PDO("mysql:host=localhost;dbname=".$db_user.";port=/var/run/mysql/mysql.sock", $db_user,$db_pass);
      }
      catch (PDOException $e) {
        $database = null;
        echo "Database connection failed.". e->getMessage(); 
      }
    }
    return database;
  }
>?