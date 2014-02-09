<?php

namespace Payutc\OnyxBundle\Basket\Exception;

use Exception;

class ExpiredPriceException extends BasketException
{
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        parent::__construct(printf('Ce tarif a expiré, veuillez en choisir un autre. %s', $message), $code, $previous);
    }
}
