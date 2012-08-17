<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeCommentmeta
 */
class MeCommentmeta
{
    /**
     * @var bigint $metaId
     */
    private $metaId;

    /**
     * @var bigint $commentId
     */
    private $commentId;

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
     * Set commentId
     *
     * @param bigint $commentId
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * Get commentId
     *
     * @return bigint 
     */
    public function getCommentId()
    {
        return $this->commentId;
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