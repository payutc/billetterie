<?php

namespace Payutc\OnyxBundle\Entity\Base;

/**
 * BaseEntity
 *
 * Base entity with basic methods.
 */
class BaseEntity
{
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

	public function toArray($recursive = false)
    {
        $entityAsArray = get_object_vars($this);

        if ($recursive) {
            foreach ($entityAsArray as &$var) {
                if ((is_object($var)) && (method_exists($var, 'toArray'))) {
                    $var = $var->toArray($recursive);
                }
            }
        }

        return $entityAsArray;
    }

    /**
     * Magic constructor
     *
     * Set up default values
     * @return BaseEntity
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return BaseEntity
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
     * @return BaseEntity
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
}