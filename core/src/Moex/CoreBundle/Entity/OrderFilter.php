<?php

namespace Moex\CoreBundle\Entity;

class OrderFilter 
{
    private $userId;

    private $orderName;

    private $orderFrom;

    private $orderTo;

    private $orderInfo;

    private $phone;

    private $price;

    private $orderTimeFrom;

    private $orderTimeTo;

    private $createdAt;

    private $updatedAt;

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setOrderName($orderName)
    {
        $this->orderName = $orderName;
    }

    public function getOrderName()
    {
        return $this->orderName;
    }

    public function setOrderFrom($orderFrom)
    {
        $this->orderFrom = $orderFrom;
    }

    public function getOrderFrom()
    {
        return $this->orderFrom;
    }

    public function setOrderTo($orderTo)
    {
        $this->orderTo = $orderTo;
    }

    public function getOrderTo()
    {
        return $this->orderTo;
    }

    public function setOrderInfo($orderInfo)
    {
        $this->orderInfo = $orderInfo;
    }

    public function getOrderInfo()
    {
        return $this->orderInfo;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setOrderTimeFrom($orderTimeFrom)
    {
        $this->orderTimeFrom = $orderTimeFrom;
    }

    public function getOrderTimeFrom()
    {
        return $this->orderTimeFrom;
    }

    public function setOrderTimeTo($orderTimeTo)
    {
        $this->orderTimeTo = $orderTimeTo;
    }

    public function getOrderTimeTo()
    {
        return $this->orderTimeTo;
    }
}
