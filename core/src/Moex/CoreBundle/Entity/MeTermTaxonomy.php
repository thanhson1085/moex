<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeTermTaxonomy
 */
class MeTermTaxonomy
{
    /**
     * @var bigint $termTaxonomyId
     */
    private $termTaxonomyId;

    /**
     * @var bigint $termId
     */
    private $termId;

    /**
     * @var string $taxonomy
     */
    private $taxonomy;

    /**
     * @var text $description
     */
    private $description;

    /**
     * @var bigint $parent
     */
    private $parent;

    /**
     * @var bigint $count
     */
    private $count;


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
     * Set termId
     *
     * @param bigint $termId
     */
    public function setTermId($termId)
    {
        $this->termId = $termId;
    }

    /**
     * Get termId
     *
     * @return bigint 
     */
    public function getTermId()
    {
        return $this->termId;
    }

    /**
     * Set taxonomy
     *
     * @param string $taxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * Get taxonomy
     *
     * @return string 
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
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
     * Set parent
     *
     * @param bigint $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return bigint 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set count
     *
     * @param bigint $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Get count
     *
     * @return bigint 
     */
    public function getCount()
    {
        return $this->count;
    }
}