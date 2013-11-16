<?php

namespace Payutc\OnyxBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use Payutc\OnyxBundle\Entity\Deletable\DeletableEntity;

/**
 * Event
 *
 * @ORM\Table(name="events")
 * @ORM\Entity(repositoryClass="Payutc\OnyxBundle\Entity\EventRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Event extends DeletableEntity
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
     * @var boolean
     *
     * @ORM\Column(name="is_hidden", type="boolean")
     */
    private $isHidden;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean")
     */
    private $isDeleted;

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
     * @ORM\Column(name="due_at", type="datetime", nullable=true)
     */
    private $dueAt;

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

    public function __construct()
    {
        parent::__construct();
        $this->isHidden = false;
        $now = new \DateTime();
        $this->dueAt = $now->add(new \DateInterval('P1W'));

        return $this;
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
     * Set dueAt
     *
     * @param \DateTime $dueAt
     * @return Event
     */
    public function setDueAt($dueAt)
    {
        $this->dueAt = $dueAt;
    
        return $this;
    }

    /**
     * Get dueAt
     *
     * @return \DateTime 
     */
    public function getDueAt()
    {
        return $this->dueAt;
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
            $this->thumbnailFile->move($this->getThumbnailUploadRootDir(), $this->thumbnailFile);

            unset($this->thumbnailFile);
        }
        if (!is_null($this->headerPictureFile)) {
            // s'il y a une erreur lors du déplacement du fichier, une exception
            // va automatiquement être lancée par la méthode move(). Cela va empêcher
            // proprement l'entité d'être persistée dans la base de données en cas d'erreur.
            $this->headerPictureFile->move($this->getHeaderPictureUploadRootDir(), $this->headerPictureFile);

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
