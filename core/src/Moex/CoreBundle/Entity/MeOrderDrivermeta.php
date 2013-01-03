<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeOrderDrivermeta
 */
class MeOrderDrivermeta
{
    /**
     * @var bigint $id
     */
    private $id;

    /**
     * @var string $metaKey
     */
    private $metaKey;

    /**
     * @var text $metaValue
     */
    private $metaValue;

    /**
     * @var Moex\CoreBundle\Entity\MeOrderDriver
     */
    private $orderdriver;


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
     * Set metaKey
     *
     * @param string $metaKey
     */
    public function setMetaKey($metaKey)
    {
        $this->metaKey = $metaKey;
    }

    /**
     * Get metaKey
     *
     * @return string 
     */
    public function getMetaKey()
    {
        return $this->metaKey;
    }

    /**
     * Set metaValue
     *
     * @param text $metaValue
     */
    public function setMetaValue($metaValue)
    {
        $this->metaValue = $metaValue;
    }

    /**
     * Get metaValue
     *
     * @return text 
     */
    public function getMetaValue()
    {
        return $this->metaValue;
    }

    /**
     * Set orderdriver
     *
     * @param Moex\CoreBundle\Entity\MeOrderDriver $orderdriver
     */
    public function setOrderdriver(\Moex\CoreBundle\Entity\MeOrderDriver $orderdriver)
    {
        $this->orderdriver = $orderdriver;
    }

    /**
     * Get orderdriver
     *
     * @return Moex\CoreBundle\Entity\MeOrderDriver 
     */
    public function getOrderdriver()
    {
        return $this->orderdriver;
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