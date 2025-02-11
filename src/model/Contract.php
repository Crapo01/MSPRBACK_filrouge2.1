<?php
namespace Lucpa\Model;

class Contract {
    private $id;     // Contract ID (Auto-Incremented)
    private $amount; // Amount for the contract

    // Constructor to initialize the contract
    public function __construct($id = null, $amount) {
        $this->id = $id;
        $this->amount = $amount;
    }

    // Getter for the ID
    public function getId() {
        return $this->id;
    }

    // Setter for the ID
    public function setId($id) {
        $this->id = $id;
    }

    // Getter for the amount
    public function getAmount() {
        return $this->amount;
    }

    // Setter for the amount
    public function setAmount($amount) {
        $this->amount = $amount;
    }
}
