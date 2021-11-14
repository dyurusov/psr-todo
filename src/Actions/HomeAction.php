<?php

namespace App\Actions;

use App\Router\RouterInterface;
use App\Template\TemplateEngineInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeAction extends AbstractAction
{
    public function __construct(TemplateEngineInterface $templateEngine, RouterInterface $router)
    {
        $this->setTemplateEngine($templateEngine);
        $this->setRouter($router);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return (new Response())
            ->withHeader('Location', $this->generateUrl(TaskActions\IndexAction::class))
            ->withStatus(301);
    }
}
