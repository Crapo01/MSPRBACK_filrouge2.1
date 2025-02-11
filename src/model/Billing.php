<?php
namespace Lucpa\Model;

class Billing {
    private $id;             // Billing ID (Auto-Incremented)
    private $contract_id;    // Contract ID (Linked to a contract)
    private $amount;         // Billing amount

    // Constructor to initialize the billing object
    public function __construct($id = null, $contract_id, $amount) {
        $this->id = $id;
        $this->contract_id = $contract_id;
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

    // Getter for the contract_id
    public function getContractId() {
        return $this->contract_id;
    }

    // Setter for the contract_id
    public function setContractId($contract_id) {
        $this->contract_id = $contract_id;
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
