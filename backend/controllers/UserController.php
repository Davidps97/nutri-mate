<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
  private $db;
  private $requestMethod;
  private $userId;

  private $user;

  public function __construct($db, $requestMethod, $userId = null)
  {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->userId = $userId;
    $this->user = new User($db);
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->userId) {
          $response = $this->getUser($this->userId);
        } else {
          $response = $this->getAllUsers();
        };
        break;
      case 'POST':
        $response = $this->createUser();
        break;
      case 'PUT':
        $response = $this->updateUser($this->userId);
        break;
      case 'DELETE':
        $response = $this->deleteUser($this->userId);
        break;
      default:
        $response = $this->notFoundResponse();
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }


  private function getAllUsers()
  {
    $result = $this->user->getAllUsers();
    $users = $result->fetchAll(PDO::FETCH_ASSOC);
    return $this->okResponse($users);
  }

  private function getUser($id)
  {
    $result = $this->user->getUser($id);
    $user = $result->fetch(PDO::FETCH_ASSOC);
    return $this->okResponse($user);
  }

  private function createUser()
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (!$this->validateUser($input)) {
      return $this->unprocessableEntityResponse();
    }

    $this->user->name = $input['name'];
    $this->user->mail = $input['mail'];
    $this->user->password = $input['password'];

    if ($this->user->create()) {
      return $this->createdResponse();
    } else {
      return $this->internalServerErrorResponse();
    }
  }

  private function validateUser($input)
  {
    if (!isset($input['name']) || !isset($input['mail']) || !isset($input['password'])) {
      return false;
    }
    return true;
  }

  private function updateUser($id)
  {
    // similar implementation
  }

  private function deleteUser($id)
  {
    // similar implementation
  }

  private function okResponse($data)
  {
    return [
      'status_code_header' => 'HTTP/1.1 200 OK',
      'body' => json_encode($data)
    ];
  }

  private function notFoundResponse()
  {
    return [
      'status_code_header' => 'HTTP/1.1 404 Not Found',
      'body' => json_encode(['message' => 'Not Found'])
    ];
  }

  private function createdResponse()
  {
    return [
      'status_code_header' => 'HTTP/1.1 201 Created',
      'body' => json_encode(['message' => 'User Created'])
    ];
  }

  private function internalServerErrorResponse()
  {
    return [
      'status_code_header' => 'HTTP/1.1 500 Internal Server Error',
      'body' => json_encode(['message' => 'Failed to create user'])
    ];
  }

  private function unprocessableEntityResponse()
  {
    return [
      'status_code_header' => 'HTTP/1.1 422 Unprocessable Entity',
      'body' => json_encode(['message' => 'Invalid input'])
    ];
  }
}
