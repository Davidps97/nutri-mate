<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../utils/Response.php';

class UserController
{
  private $db;
  private $requestMethod;
  private $userId;

  private $user;
  private $response;

  public function __construct($db, $requestMethod, $userId = null)
  {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->userId = $userId;
    $this->user = new User($db);
    $this->response = new Response();
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
        $response = $this->response->notFoundResponse();
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
    return $this->response->getResponse('HTTP/1.1 200 OK', $users);
  }

  private function getUser($id)
  {
    $result = $this->user->getUser($id);
    $user = $result->fetch(PDO::FETCH_ASSOC);
    return $this->response->getResponse('HTTP/1.1 200 OK', $user);
  }

  private function createUser()
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (!$this->validateUser($input)) {
      return $this->response->unprocessableEntityResponse();
    }

    $this->user->name = $input['name'];
    $this->user->mail = $input['mail'];
    $this->user->password = $input['password'];

    if ($this->user->create()) {
      return $this->response->getResponse('HTTP/1.1 201 Created', ['message' => 'User Created']);
    } else {
      return $this->response->getResponse('HTTP/1.1 500 Internal Server Error', ['message' => 'Failed to create user']);
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
    $this->user->id = $id;
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (!$this->validateUpdateUser($input)) {
      return $this->response->unprocessableEntityResponse();
    }

    $this->user->name = $input['name'];
    $this->user->mail = $input['mail'];

    if ($this->user->update()) {
      return $this->response->getResponse('HTTP/1.1 200 OK', ['message' => 'User Updated']);
    } else {
      return $this->response->getResponse('HTTP/1.1 500 Internal Server Error', ['message' => 'Failed to update user']);
    }
  }

  private function validateUpdateUser($input)
  {
    if (!isset($input['name']) || !isset($input['mail'])) {
      return false;
    }
    return true;
  }

  private function deleteUser($id)
  {
    $this->user->id = $id;
    $result = $this->user->delete();
    $result->fetch(PDO::FETCH_ASSOC);
    return $this->response->getResponse('HTTP/1.1 200 OK', ["message" => "User deleted"]);
  }
}
