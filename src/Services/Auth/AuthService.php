<?php

namespace App\Services\Auth;

class AuthService implements AuthServiceInterface
{
    public function authenticate(string $user, string $pass): User
    {
        if (($user !== 'admin') || ($pass !== '123')) {
            throw new \DomainException('Не верные данные входа!');
        };
        return new User();
    }
}