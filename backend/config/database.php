<?php

class Database
{

  private $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  public $conn;

  public function getConnection()
  {
    $env = parse_ini_file('.env');
    $host = $env['DB_HOST'];
    $port = $env['DB_PORT'];
    $user =  $env['DB_USER'];
    $password =  $env['DB_PASSWORD'];
    $db =  $env['DB_NAME'];
    $this->conn = null;
    try {
      $this->conn = new PDO("mysql:host=$host:$port;dbname=$db", $user, $password, $this->options);
      $this->conn->exec("SET NAMES 'utf8'");
    } catch (PDOException $exception) {
      echo "Connection error: " . $exception->getMessage();
    }
    return $this->conn;
  }
}
