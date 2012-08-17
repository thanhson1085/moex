<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeTerms
 */
class MeTerms
{
    /**
     * @var bigint $termId
     */
    private $termId;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @var bigint $termGroup
     */
    private $termGroup;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set termGroup
     *
     * @param bigint $termGroup
     */
    public function setTermGroup($termGroup)
    {
        $this->termGroup = $termGroup;
    }

    /**
     * Get termGroup
     *
     * @return bigint 
     */
    public function getTermGroup()
    {
        return $this->termGroup;
    }
}