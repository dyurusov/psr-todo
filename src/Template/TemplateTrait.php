<?php

namespace App\Template;

trait TemplateTrait
{
    protected TemplateEngineInterface $templateEngine;

    protected function setTemplateEngine(TemplateEngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    protected function render(string $template, array $params = []): string
    {
        return $this->templateEngine->render($template, $params);
    }
}