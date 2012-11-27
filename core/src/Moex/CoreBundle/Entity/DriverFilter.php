<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class DriverFilter 
{
    private $driverCode;

    private $driverName;

    private $driverAge;

    private $driverInfo;

    private $phone;

    private $position;

    public function setDriverCode($driverCode)
    {
        $this->driverCode = $driverCode;
    }

    public function getDriverCode()
    {
        return $this->driverCode;
    }

    public function setDriverName($driverName)
    {
        $this->driverName = $driverName;
    }

    public function getDriverName()
    {
        return $this->driverName;
    }

    public function setDriverAge(\int $driverAge)
    {
        $this->driverAge = $driverAge;
    }

    public function getDriverAge()
    {
        return $this->driverAge;
    }

    public function setDriverInfo($driverInfo)
    {
        $this->driverInfo = $driverInfo;
    }

    public function getDriverInfo()
    {
        return $this->driverInfo;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }
}
