<?php

namespace Payutc\OnyxBundle\Basket\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BasketException extends HttpException
{
    public function __construct($message = '', $code = 400, Exception $previous = null)
    {
        parent::__construct($code, printf('. %s', $message), $previous);
    }
}
