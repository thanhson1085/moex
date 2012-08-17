<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MePostmeta
 */
class MePostmeta
{
    /**
     * @var bigint $metaId
     */
    private $metaId;

    /**
     * @var bigint $postId
     */
    private $postId;

    /**
     * @var string $metaKey
     */
    private $metaKey;

    /**
     * @var text $metaValue
     */
    private $metaValue;


    /**
     * Get metaId
     *
     * @return bigint 
     */
    public function getMetaId()
    {
        return $this->metaId;
    }

    /**
     * Set postId
     *
     * @param bigint $postId
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    /**
     * Get postId
     *
     * @return bigint 
     */
    public function getPostId()
    {
        return $this->postId;
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
}