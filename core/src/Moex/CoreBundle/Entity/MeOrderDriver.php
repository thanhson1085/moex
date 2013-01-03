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
    /**
     * @var string $driverMoney
     */
    private $driverMoney;


    /**
     * Set driverMoney
     *
     * @param string $driverMoney
     */
    public function setDriverMoney($driverMoney)
    {
        $this->driverMoney = $driverMoney;
    }

    /**
     * Get driverMoney
     *
     * @return string 
     */
    public function getDriverMoney()
    {
        return $this->driverMoney;
    }
    /**
     * @var Moex\CoreBundle\Entity\MeOrders
     */
    private $order;

    /**
     * @var Moex\CoreBundle\Entity\MeDrivers
     */
    private $driver;


    /**
     * Set order
     *
     * @param Moex\CoreBundle\Entity\MeOrders $order
     */
    public function setOrder(\Moex\CoreBundle\Entity\MeOrders $order)
    {
        $this->order = $order;
    }

    /**
     * Get order
     *
     * @return Moex\CoreBundle\Entity\MeOrders 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set driver
     *
     * @param Moex\CoreBundle\Entity\MeDrivers $driver
     */
    public function setDriver(\Moex\CoreBundle\Entity\MeDrivers $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get driver
     *
     * @return Moex\CoreBundle\Entity\MeDrivers 
     */
    public function getDriver()
    {
        return $this->driver;
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
    /**
     * @var string $moexMoney
     */
    private $moexMoney;


    /**
     * Set moexMoney
     *
     * @param string $moexMoney
     */
    public function setMoexMoney($moexMoney)
    {
        $this->moexMoney = $moexMoney;
    }

    /**
     * Get moexMoney
     *
     * @return string 
     */
    public function getMoexMoney()
    {
        return $this->moexMoney;
    }
    /**
     * @var string $roadMoney
     */
    private $roadMoney;


    /**
     * Set roadMoney
     *
     * @param string $roadMoney
     */
    public function setRoadMoney($roadMoney)
    {
        $this->roadMoney = $roadMoney;
    }

    /**
     * Get roadMoney
     *
     * @return string 
     */
    public function getRoadMoney()
    {
        return $this->roadMoney;
    }
    /**
     * @var Moex\CoreBundle\Entity\MeOrderDrivermeta
     */
    private $orderdrivermeta;

    public function __construct()
    {
        $this->orderdrivermeta = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add orderdrivermeta
     *
     * @param Moex\CoreBundle\Entity\MeOrderDrivermeta $orderdrivermeta
     */
    public function addMeOrderDrivermeta(\Moex\CoreBundle\Entity\MeOrderDrivermeta $orderdrivermeta)
    {
        $this->orderdrivermeta[] = $orderdrivermeta;
		$orderdrivermeta->setOrderdrivermeta($this);
    }

    /**
     * Get orderdrivermeta
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrderdrivermeta()
    {
        return $this->orderdrivermeta;
    }
    public function setOrderdrvermeta($orderdrivermeta){
        $this->orderdrivermeta = $orderdrivermeta;
        foreach ($orderdrivermeta as $value){
            $value->setOrderDriver($this);
        }
    }
}
