<?php

namespace AhmedArafat\AllInOne\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class ValidationErrorsAsArrayException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
