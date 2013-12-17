<?php

namespace Payutc\OnyxBundle\Entity\Base;

/**
 * BaseEntity
 *
 * Base entity with basic methods.
 * @author Florent SCHILDKNECHT
 */
class BaseEntity
{
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
}