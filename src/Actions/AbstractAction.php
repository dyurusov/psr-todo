<?php

namespace App\Actions;

use App\Router\RouterTrait;
use App\Services\SessionServiceTrait;
use App\Template\TemplateTrait;
use Laminas\Diactoros\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


abstract class AbstractAction implements RequestHandlerInterface
{
    use TemplateTrait;
    use RouterTrait;
    use SessionServiceTrait;

    protected string $view;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->render($this->view, $this->getRenderParams($request)));
        return $response;
    }

    protected function getRenderParams(ServerRequestInterface $request): array
    {
        return array_merge(
            $this->getRouterRenderParams(),
            $this->getSessionRenderParams()
        );
    }

}