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

  public function getAllFoods()
  {
    $query = "SELECT * FROM $this->table_name";
    $stmp = $this->conn->prepare($query);
    $stmp->execute();
    return $stmp;
  }

  public function getOneFood($id)
  {
    $query = "SELECT * FROM $this->table_name WHERE id = :id";
    $stmp = $this->conn->prepare($query);
    $stmp->bindParam('id',$id);
    $stmp->execute();
    return $stmp;
  }
  
  public function create()
  {
    $query = "INSERT INTO $this->table_name (id, name, grams, proteins, carbs, fat, sugar, added_sugar) VALUES (:id, :name, :grams, :proteins, :carbs, :fat, :sugar, :added_sugar)";
    $stmp = $this->conn->prepare($query);
    $stmp->bindParam(':id',$this->id);
    $stmp->bindParam(':name',$this->name);
    $stmp->bindParam(':grams',$this->grams);
    $stmp->bindParam(':proteins',$this->proteins);
    $stmp->bindParam(':carbs',$this->carbs);
    $stmp->bindParam(':fat',$this->fat);
    $stmp->bindParam(':sugar',$this->sugar);
    $stmp->bindParam(':added_sugar',$this->added_sugar);
    $stmp->execute();
    return $stmp;
  }

  public function update()
  {
    $query = "UPDATE $this->table_name SET name = :name, grams = :grams, proteins = :proteins, carbs= :carbs, fat = :fat, sugar = :sugar, added_sugar = :added_sugar WHERE id = :id";
    $stmp = $this->conn->prepare($query);
    $stmp->bindParam(':id',$this->id);
    $stmp->bindParam(':name',$this->name);
    $stmp->bindParam(':grams',$this->grams);
    $stmp->bindParam(':proteins',$this->proteins);
    $stmp->bindParam(':carbs',$this->carbs);
    $stmp->bindParam(':fat',$this->fat);
    $stmp->bindParam(':sugar',$this->sugar);
    $stmp->bindParam(':added_sugar',$this->added_sugar);
    $stmp->execute();
    return $stmp;
  }

  public function deleteOne()
  {
    $query = "DELETE FROM $this->table_name WHERE id = :id";
    $stmp = $this->conn->prepare($query);
    $stmp->bindParam(':id',$this->id);
    $stmp->execute();
    return $stmp;
  }

}
