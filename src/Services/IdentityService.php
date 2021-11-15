<?php

namespace App\Services;

class IdentityService
{
    public function getIdentifier(): string
    {
        // fake identifier for simplicity
        return microtime(true) . '-' . rand();
    }
}