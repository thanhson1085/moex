<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeOrdermeta
 */
class MeOrdermeta
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
     * @var object $metaValue
     */
    private $metaValue;

    /**
     * @var Moex\CoreBundle\Entity\MeOrders
     */
    private $order;


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
     * @param object $metaValue
     */
    public function setMetaValue($metaValue)
    {
        $this->metaValue = $metaValue;
    }

    /**
     * Get metaValue
     *
     * @return object 
     */
    public function getMetaValue()
    {
        return $this->metaValue;
    }

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
