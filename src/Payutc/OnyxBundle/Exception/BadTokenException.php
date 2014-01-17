<?php

namespace Payutc\OnyxBundle\Exception;

use \Exception;
use \RuntimeException;

class BadTokenException extends RuntimeException
{
	public function __construct($message = '', $code = 403, Exception $previous = null)
	{
		parent::__construct(printf('Jeton invalide : %s', $message), $code, $previous);
	}
}