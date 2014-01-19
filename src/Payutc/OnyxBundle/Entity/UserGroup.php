<?php

namespace Payutc\OnyxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Payutc\OnyxBundle\Entity\Base\BaseEntity;

/**
 * UserGroup
 *
 * @ORM\Table(name="user_groups")
 * @ORM\Entity(repositoryClass="Payutc\OnyxBundle\Entity\UserGroupRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserGroup extends BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="removed_at", type="datetime", nullable=true)
     */
    private $removedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_hidden", type="boolean")
     */
    private $isHidden;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="informations", type="text", nullable=true)
     * 
     * JSON informations data for User:getMyGroups method
     */
    private $informations;

    /**
     * Magic constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->isHidden = false;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function toString()
    {
        return $this->getTitle();
    }

    /**
     * Entity has been removed or not ?
     *
     * @return boolean 
     */
    public function isDeleted()
    {
        return is_null($this->removedAt);
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserGroup
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return UserGroup
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set removedAt
     *
     * @param \DateTime $removedAt
     * @return UserGroup
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;
    
        return $this;
    }

    /**
     * Get removedAt
     *
     * @return \DateTime 
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * Set isHidden
     *
     * @param boolean $isHidden
     * @return UserGroup
     */
    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;
    
        return $this;
    }

    /**
     * Get isHidden
     *
     * @return boolean 
     */
    public function getIsHidden()
    {
        return $this->isHidden;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return UserGroup
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set informations
     *
     * @param string $informations
     * @return UserGroup
     */
    public function setInformations($informations)
    {
        $this->informations = $informations;
    
        return $this;
    }

    /**
     * Get informations
     *
     * @return string 
     */
    public function getInformations()
    {
        return $this->informations;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersist()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}