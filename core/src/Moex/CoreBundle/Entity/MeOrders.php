<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Moex\CoreBundle\Entity\MeOrders
 */
class MeOrders
{
    /**
     * @var bigint $id
     */
    private $id;

    /**
     * @var bigint $userId
     */
    private $userId;

    /**
     * @var string $orderName
     * @Assert\NotBlank()
     */
    private $orderName;

    /**
     * @var string $orderFrom
     * @Assert\NotBlank()
     */
    private $orderFrom;

    /**
     * @var string $orderTo
     * @Assert\NotBlank()
     */
    private $orderTo;

    /**
     * @var text $orderInfo
     */
    private $orderInfo;

    /**
     * @var string $phone
     */
    private $phone;

    /**
     * @var string $orderStatus
     */
    private $orderStatus;

    /**
     * @var string $price
     */
    private $price;

    /**
     * @var string $lat
     */
    private $lat;

    /**
     * @var string $lng
     */
    private $lng;

    /**
     * @var datetime $startTime
     */
    private $startTime;

    /**
     * @var datetime $createdAt
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return bigint 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param bigint $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get userId
     *
     * @return bigint 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set orderName
     *
     * @param string $orderName
     */
    public function setOrderName($orderName)
    {
        $this->orderName = $orderName;
    }

    /**
     * Get orderName
     *
     * @return string 
     */
    public function getOrderName()
    {
        return $this->orderName;
    }

    /**
     * Set orderFrom
     *
     * @param string $orderFrom
     */
    public function setOrderFrom($orderFrom)
    {
        $this->orderFrom = $orderFrom;
    }

    /**
     * Get orderFrom
     *
     * @return string 
     */
    public function getOrderFrom()
    {
        return $this->orderFrom;
    }

    /**
     * Set orderTo
     *
     * @param string $orderTo
     */
    public function setOrderTo($orderTo)
    {
        $this->orderTo = $orderTo;
    }

    /**
     * Get orderTo
     *
     * @return string 
     */
    public function getOrderTo()
    {
        return $this->orderTo;
    }

    /**
     * Set orderInfo
     *
     * @param text $orderInfo
     */
    public function setOrderInfo($orderInfo)
    {
        $this->orderInfo = $orderInfo;
    }

    /**
     * Get orderInfo
     *
     * @return text 
     */
    public function getOrderInfo()
    {
        return $this->orderInfo;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set orderStatus 
     *
     * @param string $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * Get orderStatus 
     *
     * @return string 
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set price
     *
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set lat
     *
     * @param string $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Get lng 
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng 
     *
     * @param string $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Get lng 
     *
     * @return string 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set startTime 
     *
     * @param datetime $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * Get startTime 
     *
     * @return datetime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
