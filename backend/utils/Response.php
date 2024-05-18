<?php
class Response
{
  public function getResponse($status_code_header, $body)
  {
    return [
      'status_code_header' => $status_code_header,
      'body' => json_encode($body)
    ];
  }

  public function notFoundResponse()
  {
    return [
      'status_code_header' => 'HTTP/1.1 404 Not Found',
      'body' => json_encode(['message' => 'Not Found'])
    ];
  }

  public function unprocessableEntityResponse()
  {
    return [
      'status_code_header' => 'HTTP/1.1 422 Unprocessable Entity',
      'body' => json_encode(['message' => 'Invalid input'])
    ];
  }
}
