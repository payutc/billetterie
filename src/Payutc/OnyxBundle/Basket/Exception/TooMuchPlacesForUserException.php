<?php

namespace Payutc\OnyxBundle\Basket\Exception;

use Exception;
use RuntimeException;

class TooMuchPlacesForUserException extends RuntimeException
{
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        parent::__construct(printf('Vous avez malheureusement atteint le nombre de places disponibles pour cet évènement. %s', $message), $code, $previous);
    }
}