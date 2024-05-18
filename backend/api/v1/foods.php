<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/FoodController.php';

header("Content-Type: application/json; charset=UTF-8");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$foodId = null;
if (isset($_GET['id'])) {
  $foodId = (int) $_GET['id'];
}

$database = new Database();
$db = $database->getConnection();

$controller = new FoodController($db, $requestMethod, $foodId);
$controller->processRequest();