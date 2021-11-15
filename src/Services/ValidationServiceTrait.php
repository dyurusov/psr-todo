<?php

namespace App\Services;

trait ValidationServiceTrait
{
    protected ValidationService $validationService;

    protected function setValidationService(ValidationService $validationService)
    {
        $this->validationService = $validationService;
    }
}