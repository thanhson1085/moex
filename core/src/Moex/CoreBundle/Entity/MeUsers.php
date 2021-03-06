<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeUsers
 */
class MeUsers
{
    /**
     * @var bigint $id
     */
    private $id;

    /**
     * @var string $userLogin
     */
    private $userLogin;

    /**
     * @var string $userPass
     */
    private $userPass;

    /**
     * @var string $userNicename
     */
    private $userNicename;

    /**
     * @var string $userEmail
     */
    private $userEmail;

    /**
     * @var string $userUrl
     */
    private $userUrl;

    /**
     * @var datetime $userRegistered
     */
    private $userRegistered;

    /**
     * @var string $userActivationKey
     */
    private $userActivationKey;

    /**
     * @var integer $userStatus
     */
    private $userStatus;

    /**
     * @var string $displayName
     */
    private $displayName;


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
     * Set userLogin
     *
     * @param string $userLogin
     */
    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;
    }

    /**
     * Get userLogin
     *
     * @return string 
     */
    public function getUserLogin()
    {
        return $this->userLogin;
    }

    /**
     * Set userPass
     *
     * @param string $userPass
     */
    public function setUserPass($userPass)
    {
        $this->userPass = $userPass;
    }

    /**
     * Get userPass
     *
     * @return string 
     */
    public function getUserPass()
    {
        return $this->userPass;
    }

    /**
     * Set userNicename
     *
     * @param string $userNicename
     */
    public function setUserNicename($userNicename)
    {
        $this->userNicename = $userNicename;
    }

    /**
     * Get userNicename
     *
     * @return string 
     */
    public function getUserNicename()
    {
        return $this->userNicename;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * Get userEmail
     *
     * @return string 
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set userUrl
     *
     * @param string $userUrl
     */
    public function setUserUrl($userUrl)
    {
        $this->userUrl = $userUrl;
    }

    /**
     * Get userUrl
     *
     * @return string 
     */
    public function getUserUrl()
    {
        return $this->userUrl;
    }

    /**
     * Set userRegistered
     *
     * @param datetime $userRegistered
     */
    public function setUserRegistered($userRegistered)
    {
        $this->userRegistered = $userRegistered;
    }

    /**
     * Get userRegistered
     *
     * @return datetime 
     */
    public function getUserRegistered()
    {
        return $this->userRegistered;
    }

    /**
     * Set userActivationKey
     *
     * @param string $userActivationKey
     */
    public function setUserActivationKey($userActivationKey)
    {
        $this->userActivationKey = $userActivationKey;
    }

    /**
     * Get userActivationKey
     *
     * @return string 
     */
    public function getUserActivationKey()
    {
        return $this->userActivationKey;
    }

    /**
     * Set userStatus
     *
     * @param integer $userStatus
     */
    public function setUserStatus($userStatus)
    {
        $this->userStatus = $userStatus;
    }

    /**
     * Get userStatus
     *
     * @return integer 
     */
    public function getUserStatus()
    {
        return $this->userStatus;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * @var Moex\CoreBundle\Entity\MeMoney
     */
    private $money;

    public function __construct()
    {
        $this->money = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add money
     *
     * @param Moex\CoreBundle\Entity\MeMoney $money
     */
    public function addMeMoney(\Moex\CoreBundle\Entity\MeMoney $money)
    {
        $this->money[] = $money;
    }

    /**
     * Get money
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMoney()
    {
        return $this->money;
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
     * @var Moex\CoreBundle\Entity\MeOrders
     */
    private $order;


    /**
     * Add order
     *
     * @param Moex\CoreBundle\Entity\MeOrders $order
     */
    public function addMeOrders(\Moex\CoreBundle\Entity\MeOrders $order)
    {
        $this->order[] = $order;
    }

    /**
     * Get order
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @var Moex\CoreBundle\Entity\MeUsermeta
     */
    private $usermeta;


    /**
     * Add usermeta
     *
     * @param Moex\CoreBundle\Entity\MeUsermeta $usermeta
     */
    public function addMeUsermeta(\Moex\CoreBundle\Entity\MeUsermeta $usermeta)
    {
        $this->usermeta[] = $usermeta;
    }

    /**
     * Get usermeta
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsermeta()
    {
        return $this->usermeta;
    }
}