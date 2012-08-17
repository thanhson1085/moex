<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MePosts
 */
class MePosts
{
    /**
     * @var bigint $id
     */
    private $id;

    /**
     * @var bigint $postAuthor
     */
    private $postAuthor;

    /**
     * @var datetime $postDate
     */
    private $postDate;

    /**
     * @var datetime $postDateGmt
     */
    private $postDateGmt;

    /**
     * @var text $postContent
     */
    private $postContent;

    /**
     * @var text $postTitle
     */
    private $postTitle;

    /**
     * @var text $postExcerpt
     */
    private $postExcerpt;

    /**
     * @var string $postStatus
     */
    private $postStatus;

    /**
     * @var string $commentStatus
     */
    private $commentStatus;

    /**
     * @var string $pingStatus
     */
    private $pingStatus;

    /**
     * @var string $postPassword
     */
    private $postPassword;

    /**
     * @var string $postName
     */
    private $postName;

    /**
     * @var text $toPing
     */
    private $toPing;

    /**
     * @var text $pinged
     */
    private $pinged;

    /**
     * @var datetime $postModified
     */
    private $postModified;

    /**
     * @var datetime $postModifiedGmt
     */
    private $postModifiedGmt;

    /**
     * @var text $postContentFiltered
     */
    private $postContentFiltered;

    /**
     * @var bigint $postParent
     */
    private $postParent;

    /**
     * @var string $guid
     */
    private $guid;

    /**
     * @var integer $menuOrder
     */
    private $menuOrder;

    /**
     * @var string $postType
     */
    private $postType;

    /**
     * @var string $postMimeType
     */
    private $postMimeType;

    /**
     * @var bigint $commentCount
     */
    private $commentCount;


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
     * Set postAuthor
     *
     * @param bigint $postAuthor
     */
    public function setPostAuthor($postAuthor)
    {
        $this->postAuthor = $postAuthor;
    }

    /**
     * Get postAuthor
     *
     * @return bigint 
     */
    public function getPostAuthor()
    {
        return $this->postAuthor;
    }

    /**
     * Set postDate
     *
     * @param datetime $postDate
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;
    }

    /**
     * Get postDate
     *
     * @return datetime 
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set postDateGmt
     *
     * @param datetime $postDateGmt
     */
    public function setPostDateGmt($postDateGmt)
    {
        $this->postDateGmt = $postDateGmt;
    }

    /**
     * Get postDateGmt
     *
     * @return datetime 
     */
    public function getPostDateGmt()
    {
        return $this->postDateGmt;
    }

    /**
     * Set postContent
     *
     * @param text $postContent
     */
    public function setPostContent($postContent)
    {
        $this->postContent = $postContent;
    }

    /**
     * Get postContent
     *
     * @return text 
     */
    public function getPostContent()
    {
        return $this->postContent;
    }

    /**
     * Set postTitle
     *
     * @param text $postTitle
     */
    public function setPostTitle($postTitle)
    {
        $this->postTitle = $postTitle;
    }

    /**
     * Get postTitle
     *
     * @return text 
     */
    public function getPostTitle()
    {
        return $this->postTitle;
    }

    /**
     * Set postExcerpt
     *
     * @param text $postExcerpt
     */
    public function setPostExcerpt($postExcerpt)
    {
        $this->postExcerpt = $postExcerpt;
    }

    /**
     * Get postExcerpt
     *
     * @return text 
     */
    public function getPostExcerpt()
    {
        return $this->postExcerpt;
    }

    /**
     * Set postStatus
     *
     * @param string $postStatus
     */
    public function setPostStatus($postStatus)
    {
        $this->postStatus = $postStatus;
    }

    /**
     * Get postStatus
     *
     * @return string 
     */
    public function getPostStatus()
    {
        return $this->postStatus;
    }

    /**
     * Set commentStatus
     *
     * @param string $commentStatus
     */
    public function setCommentStatus($commentStatus)
    {
        $this->commentStatus = $commentStatus;
    }

    /**
     * Get commentStatus
     *
     * @return string 
     */
    public function getCommentStatus()
    {
        return $this->commentStatus;
    }

    /**
     * Set pingStatus
     *
     * @param string $pingStatus
     */
    public function setPingStatus($pingStatus)
    {
        $this->pingStatus = $pingStatus;
    }

    /**
     * Get pingStatus
     *
     * @return string 
     */
    public function getPingStatus()
    {
        return $this->pingStatus;
    }

    /**
     * Set postPassword
     *
     * @param string $postPassword
     */
    public function setPostPassword($postPassword)
    {
        $this->postPassword = $postPassword;
    }

    /**
     * Get postPassword
     *
     * @return string 
     */
    public function getPostPassword()
    {
        return $this->postPassword;
    }

    /**
     * Set postName
     *
     * @param string $postName
     */
    public function setPostName($postName)
    {
        $this->postName = $postName;
    }

    /**
     * Get postName
     *
     * @return string 
     */
    public function getPostName()
    {
        return $this->postName;
    }

    /**
     * Set toPing
     *
     * @param text $toPing
     */
    public function setToPing($toPing)
    {
        $this->toPing = $toPing;
    }

    /**
     * Get toPing
     *
     * @return text 
     */
    public function getToPing()
    {
        return $this->toPing;
    }

    /**
     * Set pinged
     *
     * @param text $pinged
     */
    public function setPinged($pinged)
    {
        $this->pinged = $pinged;
    }

    /**
     * Get pinged
     *
     * @return text 
     */
    public function getPinged()
    {
        return $this->pinged;
    }

    /**
     * Set postModified
     *
     * @param datetime $postModified
     */
    public function setPostModified($postModified)
    {
        $this->postModified = $postModified;
    }

    /**
     * Get postModified
     *
     * @return datetime 
     */
    public function getPostModified()
    {
        return $this->postModified;
    }

    /**
     * Set postModifiedGmt
     *
     * @param datetime $postModifiedGmt
     */
    public function setPostModifiedGmt($postModifiedGmt)
    {
        $this->postModifiedGmt = $postModifiedGmt;
    }

    /**
     * Get postModifiedGmt
     *
     * @return datetime 
     */
    public function getPostModifiedGmt()
    {
        return $this->postModifiedGmt;
    }

    /**
     * Set postContentFiltered
     *
     * @param text $postContentFiltered
     */
    public function setPostContentFiltered($postContentFiltered)
    {
        $this->postContentFiltered = $postContentFiltered;
    }

    /**
     * Get postContentFiltered
     *
     * @return text 
     */
    public function getPostContentFiltered()
    {
        return $this->postContentFiltered;
    }

    /**
     * Set postParent
     *
     * @param bigint $postParent
     */
    public function setPostParent($postParent)
    {
        $this->postParent = $postParent;
    }

    /**
     * Get postParent
     *
     * @return bigint 
     */
    public function getPostParent()
    {
        return $this->postParent;
    }

    /**
     * Set guid
     *
     * @param string $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set menuOrder
     *
     * @param integer $menuOrder
     */
    public function setMenuOrder($menuOrder)
    {
        $this->menuOrder = $menuOrder;
    }

    /**
     * Get menuOrder
     *
     * @return integer 
     */
    public function getMenuOrder()
    {
        return $this->menuOrder;
    }

    /**
     * Set postType
     *
     * @param string $postType
     */
    public function setPostType($postType)
    {
        $this->postType = $postType;
    }

    /**
     * Get postType
     *
     * @return string 
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * Set postMimeType
     *
     * @param string $postMimeType
     */
    public function setPostMimeType($postMimeType)
    {
        $this->postMimeType = $postMimeType;
    }

    /**
     * Get postMimeType
     *
     * @return string 
     */
    public function getPostMimeType()
    {
        return $this->postMimeType;
    }

    /**
     * Set commentCount
     *
     * @param bigint $commentCount
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;
    }

    /**
     * Get commentCount
     *
     * @return bigint 
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }
}