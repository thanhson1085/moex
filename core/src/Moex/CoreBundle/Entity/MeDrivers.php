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
     * @var string $driverCode
     */
    private $driverCode;

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
     * @Assert\NotBlank()
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
     * Set driverCode
     *
     * @param string $driverCode
     */
    public function setDriverCode($driverCode)
    {
        $this->driverCode = $driverCode;
    }

    /**
     * Get driverCode
     *
     * @return string 
     */
    public function getDriverCode()
    {
        return $this->driverCode;
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
    /**
     * @var Moex\CoreBundle\Entity\MeMoney
     */
    private $driver_money;

    public function __construct()
    {
        $this->driver_money = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add driver_money
     *
     * @param Moex\CoreBundle\Entity\MeMoney $driverMoney
     */
    public function addMeMoney(\Moex\CoreBundle\Entity\MeMoney $driverMoney)
    {
        $this->driver_money[] = $driverMoney;
    }

    /**
     * Get driver_money
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDriverMoney()
    {
        return $this->driver_money;
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
    /**
     * @var Moex\CoreBundle\Entity\MeOrderDriver
     */
    private $driver_order;


    /**
     * Add driver_order
     *
     * @param Moex\CoreBundle\Entity\MeOrderDriver $driverOrder
     */
    public function addMeOrderDriver(\Moex\CoreBundle\Entity\MeOrderDriver $driverOrder)
    {
        $this->driver_order[] = $driverOrder;
    }

    /**
     * Get driver_order
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDriverOrder()
    {
        return $this->driver_order;
    }

	public function __toString()
	{
		return strval($this->id);
	}
    /**
     * @var string $moex_money
     */
    private $moex_money;

    /**
     * @var string $d_money
     */
    private $d_money;


    /**
     * Set moex_money
     *
     * @param string $moexMoney
     */
    public function setMoexMoney($moexMoney)
    {
        $this->moex_money = $moexMoney;
    }

    /**
     * Get moex_money
     *
     * @return string 
     */
    public function getMoexMoney()
    {
        return $this->moex_money;
    }

    /**
     * Set d_money
     *
     * @param string $dMoney
     */
    public function setDMoney($dMoney)
    {
        $this->d_money = $dMoney;
    }

    /**
     * Get d_money
     *
     * @return string 
     */
    public function getDMoney()
    {
        return $this->d_money;
    }
    /**
     * @var string $image
     */
    private $image;


    /**
     * Set image
     *
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir().'/'.$this->image;
    }

    public function getWebPath()
    {
        return null === $this->image ? null : $this->getUploadDir().'/'.$this->image;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/drivers';
    }
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
	public function upload()
	{
		// the file property can be empty if the field is not required
		if (null === $this->file) {
			return;
		}

		// use the original file name here but you should
		// sanitize it at least to avoid any security issues

		// move takes the target directory and then the target filename to move to
		$this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

		// set the path property to the filename where you'ved saved the file
		$this->image = $this->file->getClientOriginalName();

		// clean up the file property as you won't need it anymore
		$this->file = null;
	}
    /**
     * @var integer $driverType
     */
    private $driverType;


    /**
     * Set driverType
     *
     * @param integer $driverType
     */
    public function setDriverType($driverType)
    {
        $this->driverType = $driverType;
    }

    /**
     * Get driverType
     *
     * @return integer 
     */
    public function getDriverType()
    {
        return $this->driverType;
    }
}