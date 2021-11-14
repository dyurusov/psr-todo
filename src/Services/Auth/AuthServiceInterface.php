<?php

namespace App\Services\Auth;

interface AuthServiceInterface
{
    public function authenticate(string $user, string $pass): User;
}