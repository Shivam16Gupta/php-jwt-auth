<?php

class Database {
    private $db_host = 'localhost';
    private $db_name = 'quizapp';
    private $db_username = 'root';
    private $db_password = '12345';
    private $conn;

    public function dbConnection() {
        $this->conn = mysqli_connect($this->db_host, $this->db_username, $this->db_password, $this->db_name);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $this->conn;
    }

    public function __destruct() {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }
}
