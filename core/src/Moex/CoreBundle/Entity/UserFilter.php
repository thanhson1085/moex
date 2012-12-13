<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class UserFilter 
{
    private $userLogin;

    private $userEmail;

    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;
    }

    public function getUserLogin()
    {
        return $this->userLogin;
    }

    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }
}
