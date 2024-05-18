<?php
class User
{
  private $conn;
  private $table_name = "users";

  public $id;
  public $name;
  public $mail;
  public $password;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function getAllUsers()
  {
    $query = "SELECT * FROM users";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  public function getUser($id)
  {
    $query = "SELECT * FROM $this->table_name WHERE user_id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt;
  }

  public function create()
  {
    $query = "INSERT INTO $this->table_name (id, name, mail, password) VALUES (:id, :name, :mail, :password)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':mail', $this->mail);
    $stmt->bindParam(':password', $this->password);
    $stmt->execute();
    return $stmt;
  }
}
