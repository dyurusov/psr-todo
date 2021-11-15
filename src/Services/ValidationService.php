<?php

namespace App\Services;

class ValidationService
{
    public function isEmptyString(string $str): bool
    {
        return !strlen(trim($str));
    }

    public function isEmail(string $str): bool
    {
        return !!preg_match('/^[\w\-.]+@([\w-]+\.)+[\w-]{2,4}$/', trim($str));
    }
}