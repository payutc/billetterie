<?php

namespace Payutc\OnyxBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="Payutc\OnyxBundle\Entity\TicketRepository")
 */
class Ticket
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
     * @var string
     *
     * @ORM\Column(name="barcode", type="string", length=20, nullable=true)
     */
    private $barcode;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=100)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100)
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paid_at", type="datetime", nullable=true)
     */
    private $paidAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="paid_price", type="integer")
     */
    private $paidPrice;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="buyer_id", referencedColumnName="id")
     */
    private $buyer;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     */
    private $seller;

    /**
     * @ORM\ManyToOne(targetEntity="Price")
     * @ORM\JoinColumn(name="price_id", referencedColumnName="id")
     */
    private $price;

    /**
     * Magic constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        return $this;
    }

    public function __toString()
    {
        return $this->getBarcode();
    }

    public function toString()
    {
        return $this->getBarcode();
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
     * @return Ticket
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
     * @return Ticket
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
     * @return Ticket
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
     * Set barcode
     *
     * @param string $barcode
     * @return Ticket
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    
        return $this;
    }

    /**
     * Get barcode
     *
     * @return string 
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Ticket
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Ticket
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set paidAt
     *
     * @param \DateTime $paidAt
     * @return Ticket
     */
    public function setPaidAt($paidAt)
    {
        $this->paidAt = $paidAt;
    
        return $this;
    }

    /**
     * Get paidAt
     *
     * @return \DateTime 
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * Set paidPrice
     *
     * @param integer $paidPrice
     * @return Ticket
     */
    public function setPaidPrice($paidPrice)
    {
        $this->paidPrice = $paidPrice;
    
        return $this;
    }

    /**
     * Get paidPrice
     *
     * @return integer 
     */
    public function getPaidPrice()
    {
        return $this->paidPrice;
    }

    /**
     * Set buyer
     *
     * @param User $buyer
     * @return Ticket
     */
    public function setBuyer(User $buyer = null)
    {
        $this->buyer = $buyer;
    
        return $this;
    }

    /**
     * Get buyer
     *
     * @return User 
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set seller
     *
     * @param User $seller
     * @return Ticket
     */
    public function setSeller(User $seller = null)
    {
        $this->seller = $seller;
    
        return $this;
    }

    /**
     * Get seller
     *
     * @return User 
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set price
     *
     * @param Price $price
     * @return Ticket
     */
    public function setPrice(Price $price = null)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return Price 
     */
    public function getPrice()
    {
        return $this->price;
    }
}