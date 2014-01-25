<?php

namespace Payutc\OnyxBundle\Basket\Exception;

use Exception;
use RuntimeException;

class TooMuchPlacesOfPriceForUserException extends RuntimeException
{
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        parent::__construct(printf('Vous avez malheureusement atteint le nombre de places disponibles pour ce prix. %s', $message), $code, $previous);
    }
}