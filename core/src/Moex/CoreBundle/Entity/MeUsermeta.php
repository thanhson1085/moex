<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeUsermeta
 */
class MeUsermeta
{
    /**
     * @var bigint $umetaId
     */
    private $umetaId;

    /**
     * @var bigint $userId
     */
    private $userId;

    /**
     * @var string $metaKey
     */
    private $metaKey;

    /**
     * @var text $metaValue
     */
    private $metaValue;


    /**
     * Get umetaId
     *
     * @return bigint 
     */
    public function getUmetaId()
    {
        return $this->umetaId;
    }

    /**
     * Set userId
     *
     * @param bigint $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get userId
     *
     * @return bigint 
     */
    public function getUserId()
    {
        return $this->userId;
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