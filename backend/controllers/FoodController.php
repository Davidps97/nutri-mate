<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/FoodModel.php';
require_once __DIR__ . '/../utils/Response.php';

class FoodController
{
  private $db;
  private $requestMethod;
  private $foodId;

  private $food;

  private $response;

  public function __construct($db, $requestMethod,$foodId = null)
  {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->foodId = $foodId;
    $this->food = new Food($db);
    $this->response = new Response();
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->foodId) {
          $response = $this->getFood($this->foodId);
        } else {
          $response = $this->getAllFoods();
        };
        break;
      case 'POST':
        $response = $this->createFood();
        break;
      case 'PUT':
        $response = $this->updateFood($this->foodId);
        break;
      case 'DELETE':
        $response = $this->deleteFood($this->foodId);
        break;
      default:
        $response = $this->response->notFoundResponse();
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  private function getAllFoods()
  {
    $result = $this->food->getAllFoods();
    $foods = $result->fetchAll(PDO::FETCH_ASSOC);
    return $this->response->getResponse('HTTP/1.1 200 OK', $foods);
  }

  private function getFood($id)
  {
    $result = $this->food->getOneFood($id);
    $food = $result->fetchAll(PDO::FETCH_ASSOC);
    return $this->response->getResponse('HTTP/1.1 200 OK', $food);
  }

  private function createFood()
  {
    $input = (array) json_decode(file_get_contents('php://input'),TRUE);
    if(!$this->validateFood($input)){
      return $this->response->unprocessableEntityResponse();
    }

    $this->food->name = $input['name'];    
    $this->food->grams = $input['grams'];    
    $this->food->proteins = $input['proteins'];    
    $this->food->carbs = $input['carbs'];    
    $this->food->fat = $input['fat'];    
    $this->food->sugar = $input['sugar'];    
    $this->food->added_sugar = $input['added_sugar'];   
    
    if ($this->food->create()) {
      return $this->response->getResponse('HTTP/1.1 201 Created', ['message' => 'Food Created']);
    } else {
      return $this->response->getResponse('HTTP/1.1 500 Internal Server Error', ['message' => 'Failed to create food']);
    }
  }
  
  private function validateFood($input)
  {
    if (!isset($input['name']) || !isset($input['grams']) || !isset($input['proteins'])|| !isset($input['carbs']) || !isset($input['fat'])|| !isset($input['sugar']) || !isset($input['added_sugar'])) {
      return false;
    }
    return true;
  }

  private function updateFood($id)
  {
    $this->food->id = $id;
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (!$this->validateUpdateFood($input)) {
      return $this->response->unprocessableEntityResponse();
    }
  
    $this->food->name = $input['name'];
    $this->food->grams = $input['grams'];
    $this->food->proteins = $input['proteins'];
    $this->food->carbs = $input['carbs'];
    $this->food->fat = $input['fat'];
    $this->food->sugar = $input['sugar'];
    $this->food->added_sugar = $input['added_sugar'];

    if ($this->food->update()) {
      return $this->response->getResponse('HTTP/1.1 200 OK', ['message' => 'Food Updated']);
    } else {
      return $this->response->getResponse('HTTP/1.1 500 Internal Server Error', ['message' => 'Failed to update food']);
    }
  }

  private function validateUpdateFood($input)
  {
    if (!isset($input['name']) || !isset($input['grams']) || !isset($input['proteins'])|| !isset($input['carbs']) || !isset($input['fat'])|| !isset($input['sugar']) || !isset($input['added_sugar'])) {
      return false;
    }
    return true;
  }

  private function deleteFood($id)
  {
    $this->food->id = $id;
    $result = $this->food->deleteOne();
    $result->fetch(PDO::FETCH_ASSOC);
    return $this->response->getResponse('HTTP/1.1 200 OK', ["message" => "Food deleted"]);
  }

}