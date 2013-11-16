<?php

namespace Payutc\OnyxBundle\Entity\Deletable;

/**
 * DeletableEntityRepositoryInterface
 *
 * Contains mandatory methods for all repositories of entities with a is_deleted property.
 */
class DeletableEntityRepositoryInterface
{
	/**
     * Find all entities that have is_deleted property set up to false.
     *
     * @return array
     */
	public function findAllNotDeleted();

	/**
     * Find all entities that have is_deleted and is_hidden property set up to false.
     *
     * @return array
     */
	public function findAllActive();

	/**
     * Find one entity by id that have is_deleted and is_hidden property set up to false.
     *
     * @return array
     */
	public function findOneActive(int $id);
}