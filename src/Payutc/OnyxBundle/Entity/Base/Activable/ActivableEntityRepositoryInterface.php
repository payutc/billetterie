<?php

namespace Payutc\OnyxBundle\Entity\Base\Activable;

/**
 * DeletableEntityRepositoryInterface
 *
 * Contains mandatory methods for all repositories of entities with a is_deleted property.
 * @author Florent SCHILDKNECHT
 */
interface ActivableEntityRepositoryInterface
{
     /**
     * Find all entities that have is_deleted and is_hidden property set up to false.
     *
     * @return array
     */
     public function findAllActive();
}