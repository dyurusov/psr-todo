<?php

namespace App\Template;

class TemplateEngine implements TemplateEngineInterface
{
    // TODO: create only once
    private $twig;

    public function __construct($path)
    {
        $loader = new \Twig\Loader\FilesystemLoader($path);
        $this->twig = new \Twig\Environment($loader);
    }

    public function render(string $template, array $params = []): string
    {
        return $this->twig->render("$template.html", $params);
    }
}