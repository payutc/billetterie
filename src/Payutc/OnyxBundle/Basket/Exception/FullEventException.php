<?php

namespace Payutc\OnyxBundle\Basket\Exception;

use Exception;
use RuntimeException;

class FullEventException extends RuntimeException
{
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        parent::__construct(printf('Cet évènement est malheureusement complet. %s', $message), $code, $previous);
    }
}