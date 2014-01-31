<?php

namespace Payutc\OnyxBundle\Basket\Exception;

class FullEventException extends BasketException
{
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        parent::__construct(printf('Cet évènement est malheureusement complet. %s', $message), $code, $previous);
    }
}