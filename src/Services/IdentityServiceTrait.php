<?php

namespace App\Services;

trait IdentityServiceTrait
{
    protected IdentityService $identityService;

    protected function setIdentityService(IdentityService $identityService)
    {
        $this->identityService = $identityService;
    }

    protected function getNewIdentifier(): string{
        return $this->identityService->getIdentifier();
    }
}