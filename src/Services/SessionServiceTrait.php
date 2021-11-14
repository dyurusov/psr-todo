<?php

namespace App\Services;

trait SessionServiceTrait
{
    protected SessionService $sessionService;

    protected function setSessionService(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    protected function getSessionRenderParams(): array
    {
        return [
            'isAdmin' => $this->sessionService->getIsAdmin(),
            'message' => $this->sessionService->getFlash(),
        ];
    }
}