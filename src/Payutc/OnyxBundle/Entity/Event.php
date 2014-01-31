<?php

namespace Payutc\OnyxBundle\Entity;

use Serializable;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use Payutc\OnyxBundle\Entity\Base\BaseEntity;

/**
 * Event
 *
 * @ORM\Table(name="events")
 * @ORM\Entity(repositoryClass="Payutc\OnyxBundle\Entity\EventRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Event extends BaseEntity implements Serializable
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
     * @Assert\NotBlank
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_at", type="datetime", nullable=true)
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
     * @ORM\Column(name="thumbnail", type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @Assert\File(maxSize="3000000")
     */
    private $thumbnailFile;

    /**
     * @var string
     *
     * @ORM\Column(name="header_picture", type="string", length=255, nullable=true)
     */
    private $headerPicture;

    /**
     * @Assert\File(maxSize="3000000")
     */
    private $headerPictureFile;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

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
     * @var integer
     *
     * @ORM\Column(name="fundation_id", type="integer")
     * Payutc link
     */
    private $fundationId;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->isHidden = false;
        $this->capacity = 1;
        $this->maxPlacesForUser = 1;
        $now = new \DateTime();
        $this->dueAt = $now->add(new \DateInterval('P1W'));

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function toString()
    {
        return $this->title;
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
     * @return Event
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
     * @return Event
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
     * @return Event
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
     * @return Event
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
     * @return Event
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
     * Set startAt
     *
     * @param \DateTime $startAt
     * @return Event
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
     * @return Event
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
     * Set thumbnail
     *
     * @param string $thumbnail
     * @return Event
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    
        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string 
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set thumbnailFile
     *
     * @param string $thumbnailFile
     * @return Event
     */
    public function setThumbnailFile($thumbnailFile)
    {
        $this->thumbnailFile = $thumbnailFile;
    
        return $this;
    }

    /**
     * Get thumbnailFile
     *
     * @return string 
     */
    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    /**
     * Set headerPicture
     *
     * @param string $headerPicture
     * @return Event
     */
    public function setHeaderPicture($headerPicture)
    {
        $this->headerPicture = $headerPicture;
    
        return $this;
    }

    /**
     * Get headerPicture
     *
     * @return string 
     */
    public function getHeaderPicture()
    {
        return $this->headerPicture;
    }

    /**
     * Set headerPictureFile
     *
     * @param string $headerPictureFile
     * @return Event
     */
    public function setHeaderPictureFile($headerPictureFile)
    {
        $this->headerPictureFile = $headerPictureFile;
    
        return $this;
    }

    /**
     * Get headerPictureFile
     *
     * @return string 
     */
    public function getHeaderPictureFile()
    {
        return $this->headerPictureFile;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Event
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     * @return Event
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
     * @return Event
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
     * Set fundationId
     *
     * @param integer $fundationId
     * @return Event
     */
    public function setFundationId($fundationId)
    {
        $this->fundationId = $fundationId;
    
        return $this;
    }

    /**
     * Get fundationId
     *
     * @return integer 
     */
    public function getFundationId()
    {
        return $this->fundationId;
    }

    //
    // UPLOAD FILE MANAGEMENT
    // for thumbnail and headerPicture files
    // This process uses the entity lifecyclecallback of Symfony2, uploading automatically the files based on persist events.
    //

    /**
     * Get thumbnail absolute path
     */
    public function getThumbnailAbsolutePath()
    {
        return (is_null($this->thumbnail) ? null : $this->getThumbnailUploadRootDir() . '/' . $this->thumbnail);
    }

    /**
     * Get thumbnail web path, used to display images
     */
    public function getThumbnailWebPath()
    {
        return (is_null($this->thumbnail) ? null : $this->getThumbnailUploadDir() . '/' . $this->thumbnail);
    }

    /**
     * Get thumbnail upload root dir, relative to this folder
     */
    protected function getThumbnailUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getThumbnailUploadDir();
    }

    /**
     * Get thumbnail upload dir, relative to the web/ folder
     */
    protected function getThumbnailUploadDir()
    {
        // Le chemin relatif au dossier web/
        return 'uploads/events';
    }

    /**
     * Get headerPicture absolute path
     */
    public function getHeaderPictureAbsolutePath()
    {
        return (is_null($this->headerPicture) ? null : $this->getHeaderPictureUploadRootDir() . '/' . $this->headerPicture);
    }

    /**
     * Get headerPicture web path, used to display images
     */
    public function getHeaderPictureWebPath()
    {
        return (is_null($this->headerPicture) ? null : $this->getHeaderPictureUploadDir() . '/' . $this->headerPicture);
    }

    /**
     * Get headerPicture upload root dir, relative to this folder
     */
    protected function getHeaderPictureUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getHeaderPictureUploadDir();
    }

    /**
     * Get headerPicture upload dir, relative to the web/ folder
     */
    protected function getHeaderPictureUploadDir()
    {
        // Le chemin relatif au dossier web/
        return 'uploads/events';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        $this->setUpdatedAt(new \DateTime());
        if (!is_null($this->thumbnailFile)) {
            $this->thumbnail = sha1(uniqid(mt_rand(), true)) . '.' . $this->thumbnailFile->guessExtension();
        }
        if (!is_null($this->headerPictureFile)) {
            $this->headerPicture = sha1(uniqid(mt_rand(), true)) . '.' . $this->headerPictureFile->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (!is_null($this->thumbnailFile)) {
            // s'il y a une erreur lors du déplacement du fichier, une exception
            // va automatiquement être lancée par la méthode move(). Cela va empêcher
            // proprement l'entité d'être persistée dans la base de données en cas d'erreur.
            $this->thumbnailFile->move($this->getThumbnailUploadRootDir(), $this->thumbnail);

            unset($this->thumbnailFile);
        }
        if (!is_null($this->headerPictureFile)) {
            // s'il y a une erreur lors du déplacement du fichier, une exception
            // va automatiquement être lancée par la méthode move(). Cela va empêcher
            // proprement l'entité d'être persistée dans la base de données en cas d'erreur.
            $this->headerPictureFile->move($this->getHeaderPictureUploadRootDir(), $this->headerPicture);

            unset($this->headerPictureFile);
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($thumbnail = $this->getThumbnailAbsolutePath()) {
            unlink($thumbnail);
        }
        if ($headerPicture = $this->getHeaderPictureAbsolutePath()) {
            unlink($headerPicture);
        }
    }
}