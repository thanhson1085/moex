<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeOrderDriver
 */
class MeOrderDriver
{
    /**
     * @var bigint $id
     */
    private $id;

    /**
     * @var bigint $driverId
     */
    private $driverId;


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
     * Set driverId
     *
     * @param bigint $driverId
     */
    public function setDriverId($driverId)
    {
        $this->driverId = $driverId;
    }

    /**
     * Get driverId
     *
     * @return bigint 
     */
    public function getDriverId()
    {
        return $this->driverId;
    }
    /**
     * @var bigint $orderId
     */
    private $orderId;


    /**
     * Set orderId
     *
     * @param bigint $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Get orderId
     *
     * @return bigint 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }
    /**
     * @var string $money
     */
    private $money;

    /**
     * @var datetime $createdAt
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     */
    private $updatedAt;


    /**
     * Set money
     *
     * @param string $money
     */
    public function setMoney($money)
    {
        $this->money = $money;
    }

    /**
     * Get money
     *
     * @return string 
     */
    public function getMoney()
    {
        return $this->money;
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