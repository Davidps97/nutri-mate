<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/UserController.php';

header("Content-Type: application/json; charset=UTF-8");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$userId = null;
if (isset($_GET['id'])) {
  $userId = (int) $_GET['id'];
}

$database = new Database();
$db = $database->getConnection();

$controller = new UserController($db, $requestMethod, $userId);
$controller->processRequest();