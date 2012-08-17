<?php

namespace Moex\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moex\CoreBundle\Entity\MeLinks
 */
class MeLinks
{
    /**
     * @var bigint $linkId
     */
    private $linkId;

    /**
     * @var string $linkUrl
     */
    private $linkUrl;

    /**
     * @var string $linkName
     */
    private $linkName;

    /**
     * @var string $linkImage
     */
    private $linkImage;

    /**
     * @var string $linkTarget
     */
    private $linkTarget;

    /**
     * @var string $linkDescription
     */
    private $linkDescription;

    /**
     * @var string $linkVisible
     */
    private $linkVisible;

    /**
     * @var bigint $linkOwner
     */
    private $linkOwner;

    /**
     * @var integer $linkRating
     */
    private $linkRating;

    /**
     * @var datetime $linkUpdated
     */
    private $linkUpdated;

    /**
     * @var string $linkRel
     */
    private $linkRel;

    /**
     * @var text $linkNotes
     */
    private $linkNotes;

    /**
     * @var string $linkRss
     */
    private $linkRss;


    /**
     * Get linkId
     *
     * @return bigint 
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * Set linkUrl
     *
     * @param string $linkUrl
     */
    public function setLinkUrl($linkUrl)
    {
        $this->linkUrl = $linkUrl;
    }

    /**
     * Get linkUrl
     *
     * @return string 
     */
    public function getLinkUrl()
    {
        return $this->linkUrl;
    }

    /**
     * Set linkName
     *
     * @param string $linkName
     */
    public function setLinkName($linkName)
    {
        $this->linkName = $linkName;
    }

    /**
     * Get linkName
     *
     * @return string 
     */
    public function getLinkName()
    {
        return $this->linkName;
    }

    /**
     * Set linkImage
     *
     * @param string $linkImage
     */
    public function setLinkImage($linkImage)
    {
        $this->linkImage = $linkImage;
    }

    /**
     * Get linkImage
     *
     * @return string 
     */
    public function getLinkImage()
    {
        return $this->linkImage;
    }

    /**
     * Set linkTarget
     *
     * @param string $linkTarget
     */
    public function setLinkTarget($linkTarget)
    {
        $this->linkTarget = $linkTarget;
    }

    /**
     * Get linkTarget
     *
     * @return string 
     */
    public function getLinkTarget()
    {
        return $this->linkTarget;
    }

    /**
     * Set linkDescription
     *
     * @param string $linkDescription
     */
    public function setLinkDescription($linkDescription)
    {
        $this->linkDescription = $linkDescription;
    }

    /**
     * Get linkDescription
     *
     * @return string 
     */
    public function getLinkDescription()
    {
        return $this->linkDescription;
    }

    /**
     * Set linkVisible
     *
     * @param string $linkVisible
     */
    public function setLinkVisible($linkVisible)
    {
        $this->linkVisible = $linkVisible;
    }

    /**
     * Get linkVisible
     *
     * @return string 
     */
    public function getLinkVisible()
    {
        return $this->linkVisible;
    }

    /**
     * Set linkOwner
     *
     * @param bigint $linkOwner
     */
    public function setLinkOwner($linkOwner)
    {
        $this->linkOwner = $linkOwner;
    }

    /**
     * Get linkOwner
     *
     * @return bigint 
     */
    public function getLinkOwner()
    {
        return $this->linkOwner;
    }

    /**
     * Set linkRating
     *
     * @param integer $linkRating
     */
    public function setLinkRating($linkRating)
    {
        $this->linkRating = $linkRating;
    }

    /**
     * Get linkRating
     *
     * @return integer 
     */
    public function getLinkRating()
    {
        return $this->linkRating;
    }

    /**
     * Set linkUpdated
     *
     * @param datetime $linkUpdated
     */
    public function setLinkUpdated($linkUpdated)
    {
        $this->linkUpdated = $linkUpdated;
    }

    /**
     * Get linkUpdated
     *
     * @return datetime 
     */
    public function getLinkUpdated()
    {
        return $this->linkUpdated;
    }

    /**
     * Set linkRel
     *
     * @param string $linkRel
     */
    public function setLinkRel($linkRel)
    {
        $this->linkRel = $linkRel;
    }

    /**
     * Get linkRel
     *
     * @return string 
     */
    public function getLinkRel()
    {
        return $this->linkRel;
    }

    /**
     * Set linkNotes
     *
     * @param text $linkNotes
     */
    public function setLinkNotes($linkNotes)
    {
        $this->linkNotes = $linkNotes;
    }

    /**
     * Get linkNotes
     *
     * @return text 
     */
    public function getLinkNotes()
    {
        return $this->linkNotes;
    }

    /**
     * Set linkRss
     *
     * @param string $linkRss
     */
    public function setLinkRss($linkRss)
    {
        $this->linkRss = $linkRss;
    }

    /**
     * Get linkRss
     *
     * @return string 
     */
    public function getLinkRss()
    {
        return $this->linkRss;
    }
}