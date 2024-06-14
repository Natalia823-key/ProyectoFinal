<?php
//Conexión a la base de datos
class Database {
  private $servername = "localhost";
  private $username = "root";
  private $password = "";
  private $dbname = "agricultor";

  public $conn;
  public function getConnection() {
    $this->conn = null;
    try{
        $this->conn = new PDO('mysql:host='. $this->servername .';dbname='. $this->dbname, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }
    return $this->conn;
  }
  
}
