<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeTermRelationships
 */
class MeTermRelationships
{
    /**
     * @var bigint $objectId
     */
    private $objectId;

    /**
     * @var bigint $termTaxonomyId
     */
    private $termTaxonomyId;

    /**
     * @var integer $termOrder
     */
    private $termOrder;


    /**
     * Set objectId
     *
     * @param bigint $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    /**
     * Get objectId
     *
     * @return bigint 
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set termTaxonomyId
     *
     * @param bigint $termTaxonomyId
     */
    public function setTermTaxonomyId($termTaxonomyId)
    {
        $this->termTaxonomyId = $termTaxonomyId;
    }

    /**
     * Get termTaxonomyId
     *
     * @return bigint 
     */
    public function getTermTaxonomyId()
    {
        return $this->termTaxonomyId;
    }

    /**
     * Set termOrder
     *
     * @param integer $termOrder
     */
    public function setTermOrder($termOrder)
    {
        $this->termOrder = $termOrder;
    }

    /**
     * Get termOrder
     *
     * @return integer 
     */
    public function getTermOrder()
    {
        return $this->termOrder;
    }
}