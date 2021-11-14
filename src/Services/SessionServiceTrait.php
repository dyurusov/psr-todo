<?php

namespace App\Services;

trait SessionServiceTrait
{
    protected SessionService $sessionService;

    protected function setSessionService(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }
}