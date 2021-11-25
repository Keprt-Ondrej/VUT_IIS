<?php 
  class database {

    private $db_user  = 'xkeprt03';
    private $db_pass  = 'otoj5epu';
    private $db;


    public function init() {
      
      try {
       $this->db = new PDO("mysql:host=localhost;dbname=".$this->db_user.";port=/var/run/mysql/mysql.sock", 
        $this->db_user,$this->db_pass);
      }
      catch (PDOException $e) {
        $this->db = null;
        echo "Database connection failed.". $e->getMessage(); 
      }
      return $this->db;
    }

    
  }

?>
