<?php

namespace App\Error\Exceptions;


class ForbiddenException extends HttpException
{
    public function __construct()
    {
        parent::__construct(403, 'Не доступа');
    }
}