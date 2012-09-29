<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Moex\CoreBundle\Entity\MeDrivers
 */
class MeDrivers
{
    /**
     * @var bigint $id
     */
    private $id;

    /**
     * @var string $driverName
     */
    private $driverName;

    /**
     * @var int $driverAge
     */
    private $driverAge;

    /**
     * @var text $driverInfo
     */
    private $driverInfo;

    /**
     * @var string $motoNo
     */
    private $motoNo;

    /**
     * @var string $phone
     */
    private $phone;

    /**
     * @var string $position
     */
    private $position;

    /**
     * @var string $money
     */
    private $money;

    /**
     * @var string $lat
     */
    private $lat;

    /**
     * @var string $lng
     */
    private $lng;

    /**
     * @var datetime $createdAt
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     */
    private $updatedAt;

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
     * Set driverName
     *
     * @param string $driverName
     */
    public function setDriverName($driverName)
    {
        $this->driverName = $driverName;
    }

    /**
     * Get driverName
     *
     * @return string 
     */
    public function getDriverName()
    {
        return $this->driverName;
    }

    /**
     * Set driverAge
     *
     * @param int $driverAge
     */
    public function setDriverAge($driverAge)
    {
        $this->driverAge = $driverAge;
    }

    /**
     * Get driverAge
     *
     * @return int 
     */
    public function getDriverAge()
    {
        return $this->driverAge;
    }

    /**
     * Set motoNo
     *
     * @param string $motoNo
     */
    public function setMotoNo($motoNo)
    {
        $this->motoNo = $motoNo;
    }

    /**
     * Get motoNo
     *
     * @return string 
     */
    public function getMotoNo()
    {
        return $this->motoNo;
    }

    /**
     * Set driverInfo
     *
     * @param text $driverInfo
     */
    public function setDriverInfo($driverInfo)
    {
        $this->driverInfo = $driverInfo;
    }

    /**
     * Get driverInfo
     *
     * @return text 
     */
    public function getDriverInfo()
    {
        return $this->driverInfo;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set position
     *
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set money 
     *
     * @param string $money
     */
    public function setMoney($money)
    {
        $this->money = $money;
    }

    /**
     * Get money 
     *
     * @return string 
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Set  lat
     *
     * @param string $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Get lat 
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng 
     *
     * @param string $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLng()
    {
        return $this->lng;
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
}
