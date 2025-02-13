<?php
namespace Lucpa\Model;

class Vehicle {
    private $id;
    private $model;
    private $licencePlate;
    private $informations;
    private $km;

    
    public function __construct($id = null, $model = null, $licencePlate = null, $informations = null, $km = null) {
        $this->id = $id;
        $this->model = $model;
        $this->licencePlate = $licencePlate;
        $this->informations = $informations;
        $this->km = $km;
    }

    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    
    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {
        $this->model = $model;
    }

    
    public function getLicencePlate() {
        return $this->licencePlate;
    }

    public function setLicencePlate($licencePlate) {
        $this->licencePlate = $licencePlate;
    }

    
    public function getInformations() {
        return $this->informations;
    }

    public function setInformations($informations) {
        $this->informations = $informations;
    }

    
    public function getKm() {
        return $this->km;
    }

    public function setKm($km) {
        $this->km = $km;
    }
}
