<?php

namespace Lucpa\Model;

class Billing
{
    private $id;
    private $contract_id;
    private $amount;
    public function __construct($id = null, $contract_id, $amount)
    {
        $this->contract_id = $contract_id;
        $this->amount = $amount;
        $this->id = $id;
    }

    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }


    public function getContractId()
    {
        return $this->contract_id;
    }


    public function setContractId($contract_id)
    {
        $this->contract_id = $contract_id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}
