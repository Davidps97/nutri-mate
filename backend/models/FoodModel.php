<?php
class Food
{
  private $conn;
  private $table_name = "foods";

  public $id;
  public $name;
  public $grams;
  public $proteins;
  public $carbs;
  public $fat;
  public $sugar;
  public $added_sugar;

  public function __construct($db)
  {
    $this->conn = $db;
  }
}
