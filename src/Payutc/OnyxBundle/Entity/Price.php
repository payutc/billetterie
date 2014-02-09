<?php

namespace Payutc\OnyxBundle\Entity;

use Serializable;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Payutc\OnyxBundle\Entity\Base\BaseEntity;

/**
 * Price
 *
 * @ORM\Table(name="prices")
 * @ORM\Entity(repositoryClass="Payutc\OnyxBundle\Entity\PriceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Price extends BaseEntity implements Serializable
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_at", type="datetime")
     */
    private $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_at", type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_nominative", type="boolean")
     */
    private $isNominative;

    /**
     * @var integer
     *
     * @ORM\Column(name="capacity", type="integer")
     */
    private $capacity;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_places_for_user", type="integer")
     */
    private $maxPlacesForUser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_unique_for_user", type="boolean")
     */
    private $isUniqueForUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="payutc_id", type="integer", nullable=true)
     */
    private $payutcId;

    /**
     * @ORM\ManyToMany(targetEntity="UserGroup")
     * @ORM\JoinTable(name="prices_usergroups",
     *      joinColumns={@ORM\JoinColumn(name="price_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="usergroup_id", referencedColumnName="id")}
     * )
     */
    private $userGroups;

    /**
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * Magic constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->isHidden = false;
        $this->isNominative = false;
        $this->price = 0;
        $this->capacity = 1;
        $this->maxPlacesForUser = 1;
        $this->isUniqueForUser = false;
        $this->userGroups = new ArrayCollection();

        return $this;
    }

    /**
     * Magic __toString function
     * Returns the title of the price
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * toString function
     * Returns the title of the price
     *
     * @return string
     */
    public function toString()
    {
        return $this->getTitle();
    }

    /**
     * Get title and price
     * Form helper
     *
     * @return string
     */
    public function getTitleAndPrice()
    {
        return $this->getTitle() . ' - ' . $this->getPrice() . '€';
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
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
     * @return Price
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
     * @return Price
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
     * @return Price
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
     * @return Price
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
     * Set startAt
     *
     * @param \DateTime $startAt
     * @return Price
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Get startAt
     *
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set endAt
     *
     * @param \DateTime $endAt
     * @return Price
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * Get endAt
     *
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Price
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
     * Set description
     *
     * @param string $description
     * @return Price
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Price
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set isNominativ
     *
     * @param boolean $isNominativ
     * @return Price
     */
    public function setIsNominativ($isNominativ)
    {
        $this->isNominativ = $isNominativ;

        return $this;
    }

    /**
     * Get isNominativ
     *
     * @return boolean
     */
    public function getIsNominativ()
    {
        return $this->isNominativ;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     * @return Price
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return integer
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set maxPlacesForUser
     *
     * @param integer $maxPlacesForUser
     * @return Price
     */
    public function setMaxPlacesForUser($maxPlacesForUser)
    {
        $this->maxPlacesForUser = $maxPlacesForUser;

        return $this;
    }

    /**
     * Get maxPlacesForUser
     *
     * @return integer
     */
    public function getMaxPlacesForUser()
    {
        return $this->maxPlacesForUser;
    }

    /**
     * Set isUniqueForUser
     *
     * @param boolean $isUniqueForUser
     * @return Price
     */
    public function setIsUniqueForUser($isUniqueForUser)
    {
        $this->isUniqueForUser = $isUniqueForUser;

        return $this;
    }

    /**
     * Get isUniqueForUser
     *
     * @return boolean
     */
    public function getIsUniqueForUser()
    {
        return $this->isUniqueForUser;
    }

    /**
     * Set payutcId
     *
     * @param integer $payutcId
     * @return Price
     */
    public function setPayutcId($payutcId)
    {
        $this->payutcId = $payutcId;

        return $this;
    }

    /**
     * Get payutcId
     *
     * @return integer
     */
    public function getPayutcId()
    {
        return $this->payutcId;
    }

    /**
     * Set isNominative
     *
     * @param boolean $isNominative
     * @return Price
     */
    public function setIsNominative($isNominative)
    {
        $this->isNominative = $isNominative;

        return $this;
    }

    /**
     * Get isNominative
     *
     * @return boolean
     */
    public function getIsNominative()
    {
        return $this->isNominative;
    }

    /**
     * Add userGroup
     *
     * @param UserGroup $userGroup
     * @return Price
     */
    public function addUserGroup(UserGroup $userGroup)
    {
        $this->userGroups[] = $userGroup;

        return $this;
    }

    /**
     * Remove userGroup
     *
     * @param UserGroup $userGroup
     */
    public function removeUserGroup(UserGroup $userGroup)
    {
        $this->userGroups->removeElement($userGroup);
    }

    /**
     * Get userGroups
     *
     * @return ArrayCollection
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }

    /**
     * Set event
     *
     * @param Event $event
     * @return Price
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
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
