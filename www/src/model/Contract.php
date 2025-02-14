<?php
namespace Lucpa\Model;

class Contract {
    private $id;                    
    private $vehicleUid;            
    private $customerUid;           
    private $signDatetime;          
    private $locBeginDatetime;      
    private $locEndDatetime;        
    private $returningDatetime;     
    private $price;                 

    
    public function __construct($id, $vehicleUid, $customerUid, $signDatetime, $locBeginDatetime, $locEndDatetime, $returningDatetime, $price) {
        $this->id = $id;
        $this->vehicleUid = $vehicleUid;
        $this->customerUid = $customerUid;
        $this->signDatetime = $signDatetime;
        $this->locBeginDatetime = $locBeginDatetime;
        $this->locEndDatetime = $locEndDatetime;
        $this->returningDatetime = $returningDatetime;
        $this->price = $price;
    }

    
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
