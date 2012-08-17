<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string $orderFrom
     */
    private $orderFrom;

    /**
     * @var string $orderTo
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
     * @var string $price
     */
    private $price;

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