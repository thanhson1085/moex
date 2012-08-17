<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeOptions
 */
class MeOptions
{
    /**
     * @var bigint $optionId
     */
    private $optionId;

    /**
     * @var string $optionName
     */
    private $optionName;

    /**
     * @var text $optionValue
     */
    private $optionValue;

    /**
     * @var string $autoload
     */
    private $autoload;


    /**
     * Get optionId
     *
     * @return bigint 
     */
    public function getOptionId()
    {
        return $this->optionId;
    }

    /**
     * Set optionName
     *
     * @param string $optionName
     */
    public function setOptionName($optionName)
    {
        $this->optionName = $optionName;
    }

    /**
     * Get optionName
     *
     * @return string 
     */
    public function getOptionName()
    {
        return $this->optionName;
    }

    /**
     * Set optionValue
     *
     * @param text $optionValue
     */
    public function setOptionValue($optionValue)
    {
        $this->optionValue = $optionValue;
    }

    /**
     * Get optionValue
     *
     * @return text 
     */
    public function getOptionValue()
    {
        return $this->optionValue;
    }

    /**
     * Set autoload
     *
     * @param string $autoload
     */
    public function setAutoload($autoload)
    {
        $this->autoload = $autoload;
    }

    /**
     * Get autoload
     *
     * @return string 
     */
    public function getAutoload()
    {
        return $this->autoload;
    }
}