<?php

namespace Payutc\OnyxBundle\Entity\Deletable;

use Payutc\OnyxBundle\Entity\Base\BaseEntity;

/**
 * DeletableEntity
 *
 * Base entity with is_deleted property.
 */
class DeletableEntity extends BaseEntity
{
	/**
     * @var boolean
     */
	private $isDeleted;

	/**
	 * Magic constructor
	 *
	 * Set up default values
	 * @return DeletableEntity
	 */
	public function __construct()
	{
		parent::__construct();
		$this->isDeleted = false;

        return $this;
	}

	/**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Event
     */
	public function setIsDeleted($isDeleted)
	{
		$this->isDeleted = $isDeleted;

		return $this;
	}

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
	public function getIsDeleted()
	{
		return $this->isDeleted;
	}

	/**
     * Get isDeleted (alias)
     *
     * @return boolean 
     */
	public function isDeleted()
	{
		return $this->isDeleted;
	}
}