<?php
namespace Lucpa\Model;

class Vehicle {
    private $id;
    private $model;

    // Constructor to initialize a vehicle
    public function __construct($id = null, $model = null) {
        $this->id = $id;
        $this->model = $model;
    }

    // Getter and Setter for the id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and Setter for the model
    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {
        $this->model = $model;
    }
}
