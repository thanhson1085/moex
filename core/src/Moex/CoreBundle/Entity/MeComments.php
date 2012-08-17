<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeComments
 */
class MeComments
{
    /**
     * @var bigint $commentId
     */
    private $commentId;

    /**
     * @var bigint $commentPostId
     */
    private $commentPostId;

    /**
     * @var text $commentAuthor
     */
    private $commentAuthor;

    /**
     * @var string $commentAuthorEmail
     */
    private $commentAuthorEmail;

    /**
     * @var string $commentAuthorUrl
     */
    private $commentAuthorUrl;

    /**
     * @var string $commentAuthorIp
     */
    private $commentAuthorIp;

    /**
     * @var datetime $commentDate
     */
    private $commentDate;

    /**
     * @var datetime $commentDateGmt
     */
    private $commentDateGmt;

    /**
     * @var text $commentContent
     */
    private $commentContent;

    /**
     * @var integer $commentKarma
     */
    private $commentKarma;

    /**
     * @var string $commentApproved
     */
    private $commentApproved;

    /**
     * @var string $commentAgent
     */
    private $commentAgent;

    /**
     * @var string $commentType
     */
    private $commentType;

    /**
     * @var bigint $commentParent
     */
    private $commentParent;

    /**
     * @var bigint $userId
     */
    private $userId;


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
     * Set commentPostId
     *
     * @param bigint $commentPostId
     */
    public function setCommentPostId($commentPostId)
    {
        $this->commentPostId = $commentPostId;
    }

    /**
     * Get commentPostId
     *
     * @return bigint 
     */
    public function getCommentPostId()
    {
        return $this->commentPostId;
    }

    /**
     * Set commentAuthor
     *
     * @param text $commentAuthor
     */
    public function setCommentAuthor($commentAuthor)
    {
        $this->commentAuthor = $commentAuthor;
    }

    /**
     * Get commentAuthor
     *
     * @return text 
     */
    public function getCommentAuthor()
    {
        return $this->commentAuthor;
    }

    /**
     * Set commentAuthorEmail
     *
     * @param string $commentAuthorEmail
     */
    public function setCommentAuthorEmail($commentAuthorEmail)
    {
        $this->commentAuthorEmail = $commentAuthorEmail;
    }

    /**
     * Get commentAuthorEmail
     *
     * @return string 
     */
    public function getCommentAuthorEmail()
    {
        return $this->commentAuthorEmail;
    }

    /**
     * Set commentAuthorUrl
     *
     * @param string $commentAuthorUrl
     */
    public function setCommentAuthorUrl($commentAuthorUrl)
    {
        $this->commentAuthorUrl = $commentAuthorUrl;
    }

    /**
     * Get commentAuthorUrl
     *
     * @return string 
     */
    public function getCommentAuthorUrl()
    {
        return $this->commentAuthorUrl;
    }

    /**
     * Set commentAuthorIp
     *
     * @param string $commentAuthorIp
     */
    public function setCommentAuthorIp($commentAuthorIp)
    {
        $this->commentAuthorIp = $commentAuthorIp;
    }

    /**
     * Get commentAuthorIp
     *
     * @return string 
     */
    public function getCommentAuthorIp()
    {
        return $this->commentAuthorIp;
    }

    /**
     * Set commentDate
     *
     * @param datetime $commentDate
     */
    public function setCommentDate($commentDate)
    {
        $this->commentDate = $commentDate;
    }

    /**
     * Get commentDate
     *
     * @return datetime 
     */
    public function getCommentDate()
    {
        return $this->commentDate;
    }

    /**
     * Set commentDateGmt
     *
     * @param datetime $commentDateGmt
     */
    public function setCommentDateGmt($commentDateGmt)
    {
        $this->commentDateGmt = $commentDateGmt;
    }

    /**
     * Get commentDateGmt
     *
     * @return datetime 
     */
    public function getCommentDateGmt()
    {
        return $this->commentDateGmt;
    }

    /**
     * Set commentContent
     *
     * @param text $commentContent
     */
    public function setCommentContent($commentContent)
    {
        $this->commentContent = $commentContent;
    }

    /**
     * Get commentContent
     *
     * @return text 
     */
    public function getCommentContent()
    {
        return $this->commentContent;
    }

    /**
     * Set commentKarma
     *
     * @param integer $commentKarma
     */
    public function setCommentKarma($commentKarma)
    {
        $this->commentKarma = $commentKarma;
    }

    /**
     * Get commentKarma
     *
     * @return integer 
     */
    public function getCommentKarma()
    {
        return $this->commentKarma;
    }

    /**
     * Set commentApproved
     *
     * @param string $commentApproved
     */
    public function setCommentApproved($commentApproved)
    {
        $this->commentApproved = $commentApproved;
    }

    /**
     * Get commentApproved
     *
     * @return string 
     */
    public function getCommentApproved()
    {
        return $this->commentApproved;
    }

    /**
     * Set commentAgent
     *
     * @param string $commentAgent
     */
    public function setCommentAgent($commentAgent)
    {
        $this->commentAgent = $commentAgent;
    }

    /**
     * Get commentAgent
     *
     * @return string 
     */
    public function getCommentAgent()
    {
        return $this->commentAgent;
    }

    /**
     * Set commentType
     *
     * @param string $commentType
     */
    public function setCommentType($commentType)
    {
        $this->commentType = $commentType;
    }

    /**
     * Get commentType
     *
     * @return string 
     */
    public function getCommentType()
    {
        return $this->commentType;
    }

    /**
     * Set commentParent
     *
     * @param bigint $commentParent
     */
    public function setCommentParent($commentParent)
    {
        $this->commentParent = $commentParent;
    }

    /**
     * Get commentParent
     *
     * @return bigint 
     */
    public function getCommentParent()
    {
        return $this->commentParent;
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
}