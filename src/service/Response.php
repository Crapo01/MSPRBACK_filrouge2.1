<?php

namespace Lucpa\Service;

class Response
{
    private $statusCode;
    private $message;
    private $data;
        
   
    public function __construct($statusCode, $message, $data = null)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
    }
    
    public function toJson() {
        return json_encode([
            'status' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->data
        ]);
    }
    
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function getData()
    {
        return $this->data;
    }
}
