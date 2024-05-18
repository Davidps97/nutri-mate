<?php
class User
{
  private $conn;
  private $table_name = "user_food";

  public $user_id;
  public $food_id;
  public $grams;

  public function __construct($db)
  {
    $this->conn = $db;
  }
}
