<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeMoney
 */
class MeMoney
{
    /**
     * @var bigint $id
     */
    private $id;

    /**
     * @var string $amount
     */
    private $amount;

    /**
     * @var text $description
     */
    private $description;

    /**
     * @var datetime $createdAt
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     */
    private $updatedAt;

    /**
     * @var Moex\CoreBundle\Entity\MeUsers
     */
    private $user;

    /**
     * @var Moex\CoreBundle\Entity\MeDrivers
     */
    private $driver;


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
     * Set amount
     *
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
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
}