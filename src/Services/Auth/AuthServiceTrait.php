<?php

namespace App\Services\Auth;

trait AuthServiceTrait
{
    protected AuthService $authService;

    protected function setAuthService(AuthService $authService)
    {
        $this->authService = $authService;
    }
}