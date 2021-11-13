<?php

namespace App\Template;

interface TemplateEngineInterface
{
    public function render(string $template, array $params): string;
}