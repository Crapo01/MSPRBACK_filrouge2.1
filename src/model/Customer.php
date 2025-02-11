<?php
namespace Lucpa\Model;

class Customer {
    private $id;
    private $firstName;
    private $secondName;
    private $address;
    private $permitNumber;

    
    public function __construct($id = null, $firstName = null, $secondName = null, $address = null, $permitNumber = null) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->address = $address;
        $this->permitNumber = $permitNumber;
    }

   

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getSecondName() {
        return $this->secondName;
    }

    public function setSecondName($secondName) {
        $this->secondName = $secondName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getPermitNumber() {
        return $this->permitNumber;
    }

    public function setPermitNumber($permitNumber) {
        $this->permitNumber = $permitNumber;
    }
}
