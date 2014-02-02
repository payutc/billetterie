<?php

namespace Payutc\OnyxBundle\Basket\Exception;

use Exception;

class FullPriceException extends BasketException
{
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        parent::__construct(printf('Il n\'y a malheureusement plus de places disponibles à ce prix. %s', $message), $code, $previous);
    }
}
