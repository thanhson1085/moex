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
     * @var bigint $fromId
     */
    private $fromId;

    /**
     * @var bigint $toId
     */
    private $toId;

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
     * Get id
     *
     * @return bigint 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fromId
     *
     * @param bigint $fromId
     */
    public function setFromId($fromId)
    {
        $this->fromId = $fromId;
    }

    /**
     * Get fromId
     *
     * @return bigint 
     */
    public function getFromId()
    {
        return $this->fromId;
    }

    /**
     * Set toId
     *
     * @param bigint $toId
     */
    public function setToId($toId)
    {
        $this->toId = $toId;
    }

    /**
     * Get toId
     *
     * @return bigint 
     */
    public function getToId()
    {
        return $this->toId;
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