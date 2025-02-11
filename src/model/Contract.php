<?php
namespace Lucpa\Model;

class Contract {
    private $id;                    // Contract ID (Auto-Incremented)
    private $vehicleUid;            // UID of the associated vehicle
    private $customerUid;           // UID of the associated customer
    private $signDatetime;          // Date + time of contract signing
    private $locBeginDatetime;      // Date + time of rental start
    private $locEndDatetime;        // Date + time of rental end
    private $returningDatetime;     // Date + time of vehicle return
    private $price;                 // Price billed for the contract

    // Constructor to initialize the contract
    public function __construct($id = null, $vehicleUid, $customerUid, $signDatetime, $locBeginDatetime, $locEndDatetime, $returningDatetime, $price) {
        $this->id = $id;
        $this->vehicleUid = $vehicleUid;
        $this->customerUid = $customerUid;
        $this->signDatetime = $signDatetime;
        $this->locBeginDatetime = $locBeginDatetime;
        $this->locEndDatetime = $locEndDatetime;
        $this->returningDatetime = $returningDatetime;
        $this->price = $price;
    }

    // Getters and setters for each field
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getVehicleUid() { return $this->vehicleUid; }
    public function setVehicleUid($vehicleUid) { $this->vehicleUid = $vehicleUid; }

    public function getCustomerUid() { return $this->customerUid; }
    public function setCustomerUid($customerUid) { $this->customerUid = $customerUid; }

    public function getSignDatetime() { return $this->signDatetime; }
    public function setSignDatetime($signDatetime) { $this->signDatetime = $signDatetime; }

    public function getLocBeginDatetime() { return $this->locBeginDatetime; }
    public function setLocBeginDatetime($locBeginDatetime) { $this->locBeginDatetime = $locBeginDatetime; }

    public function getLocEndDatetime() { return $this->locEndDatetime; }
    public function setLocEndDatetime($locEndDatetime) { $this->locEndDatetime = $locEndDatetime; }

    public function getReturningDatetime() { return $this->returningDatetime; }
    public function setReturningDatetime($returningDatetime) { $this->returningDatetime = $returningDatetime; }

    public function getPrice() { return $this->price; }
    public function setPrice($price) { $this->price = $price; }
}
