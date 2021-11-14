<?php

namespace App\Error;

use App\Router\RouterInterface;
use App\Router\RouterTrait;
use App\Services\SessionService;
use App\Services\SessionServiceTrait;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response;
use App\Template\TemplateEngineInterface;


class ErrorHandler
{
    use RouterTrait;
    use SessionServiceTrait;

    private $templateEngine;

    public function __construct(TemplateEngineInterface $templateEngine, SessionService $sessionService, RouterInterface $router)
    {
        $this->templateEngine = $templateEngine;
        $this->setSessionService($sessionService);
        $this->setRouter($router);
    }

    public function handle(\Exception $e): ResponseInterface
    {
        $response = (new Response())
            ->withStatus($e->getCode() ?: 500);
        $response->getBody()
            ->write($this->templateEngine->render('error', array_merge(
                $this->getRouterRenderParams(),
                $this->getSessionRenderParams(),
                [ 'exception' => $e ]
            )));
        return $response;
    }
}