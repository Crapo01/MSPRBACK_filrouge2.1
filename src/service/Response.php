<?php

namespace Lucpa\Service;

class Response
{
    private $statusCode;
    private $message;
    private $data;
    
    
    // Constructor to initialize response properties
    public function __construct($statusCode, $message, $data = null)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
    }

    // Method to return the response as an array
    public function toArray()
    {
        return [
            'status_code' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
    
    // Additional method to return response as JSON
    public function toJson()
    {
        return json_encode($this->toArray());
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
