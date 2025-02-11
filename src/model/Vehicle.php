<?php
namespace Lucpa\Model;

class Vehicle {
    private $id;
    private $model;
    private $licencePlate;
    private $informations;
    private $km;

    // Constructor to initialize a vehicle
    public function __construct($id = null, $model = null, $licencePlate = null, $informations = null, $km = null) {
        $this->id = $id;
        $this->model = $model;
        $this->licencePlate = $licencePlate;
        $this->informations = $informations;
        $this->km = $km;
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

    // Getter and Setter for the licence plate
    public function getLicencePlate() {
        return $this->licencePlate;
    }

    public function setLicencePlate($licencePlate) {
        $this->licencePlate = $licencePlate;
    }

    // Getter and Setter for informations
    public function getInformations() {
        return $this->informations;
    }

    public function setInformations($informations) {
        $this->informations = $informations;
    }

    // Getter and Setter for km
    public function getKm() {
        return $this->km;
    }

    public function setKm($km) {
        $this->km = $km;
    }
}
