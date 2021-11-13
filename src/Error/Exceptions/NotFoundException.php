<?php

namespace App\Error\Exceptions;


class NotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Страница не найдена');
    }
}