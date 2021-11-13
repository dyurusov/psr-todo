<?php

namespace App\Error\Exceptions;


class HttpException extends \Exception
{
    public function __construct($code = 500, $message = null)
    {
        parent::__construct($message, $code);
    }
}