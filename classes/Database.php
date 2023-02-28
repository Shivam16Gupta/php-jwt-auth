<?php
$db = parse_ini_file(dirname(__DIR__) . "/DbProperties.ini");

class Database{
    
    // CHANGE THE DB INFO ACCORDING TO YOUR DATABASE
    private $db_host = 'localhost';
    private $db_name = 'quizapp';
    private $db_username = 'root';
    private $db_password = '12345';
    public $userData = null;

    public function dbConnection(){
        
        try{
            $conn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
          
    }

    function getUserData($data)
  { 
    foreach (getallheaders() as $name => $value) {
      if (strtoupper($name) == strtoupper($data)) {
        $this->userData =  $value;
      }
    }
    return $this->userData;
  }
}