<?php
namespace Lucpa\Model;

class Billing {
    private $id;             // Billing ID (Auto-Incremented)
    private $amount;         // Billing amount
    private $billing_date;   // Billing date

    // Constructor to initialize the billing object
    public function __construct($id = null, $amount, $billing_date) {
        $this->id = $id;
        $this->amount = $amount;
        $this->billing_date = $billing_date;
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

    // Getter for the billing date
    public function getBillingDate() {
        return $this->billing_date;
    }

    // Setter for the billing date
    public function setBillingDate($billing_date) {
        $this->billing_date = $billing_date;
    }
}
