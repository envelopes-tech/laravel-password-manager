<?php

namespace Benjafield\LaravelPasswordManager\Exceptions;

use Exception;
use Throwable;

class KeyLengthException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}