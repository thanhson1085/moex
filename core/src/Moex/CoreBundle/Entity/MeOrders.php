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
     * @var bigint $customerId
     */
    private $customerId;

    /**
     * @var string $orderCode
     */
    private $orderCode;

    /**
     * @var smallint $serviceType
     */
    private $serviceType;

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
     * @var string $distance
     */
    private $distance;

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
     * Set customerId
     *
     * @param bigint $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * Get customerId
     *
     * @return bigint 
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set orderCode
     *
     * @param string $orderCode
     */
    public function setOrderCode($orderCode)
    {
        $this->orderCode = $orderCode;
    }

    /**
     * Get orderCode
     *
     * @return string 
     */
    public function getOrderCode()
    {
        return $this->orderCode;
    }

    /**
     * Set serviceType
     *
     * @param smallint $serviceType
     */
    public function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
    }

    /**
     * Get serviceType
     *
     * @return smallint 
     */
    public function getServiceType()
    {
        return $this->serviceType;
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
     * Set distance 
     *
     * @param string $distance
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * Get distance 
     *
     * @return string 
     */
    public function getDistance()
    {
        return $this->distance;
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
    /**
     * @var Moex\CoreBundle\Entity\MeOrderDriver
     */
    private $order_driver;

    public function __construct()
    {
        $this->order_driver = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add order_driver
     *
     * @param Moex\CoreBundle\Entity\MeOrderDriver $orderDriver
     */
    public function addMeOrderDriver(\Moex\CoreBundle\Entity\MeOrderDriver $orderDriver)
    {
        $this->order_driver[] = $orderDriver;
    }

    /**
     * Get order_driver
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrderDriver()
    {
        return $this->order_driver;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        // Add your code here
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        // Add your code here
    }

	public function __toString()
	{
		return strval($this->id);
	}
    /**
     * @var Moex\CoreBundle\Entity\MeUsers
     */
    private $user;


    /**
     * Set user
     *
     * @param Moex\CoreBundle\Entity\MeUsers $user
     */
    public function setUser(\Moex\CoreBundle\Entity\MeUsers $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Moex\CoreBundle\Entity\MeUsers 
     */
    public function getUser()
    {
        return $this->user;
    }
}
