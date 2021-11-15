<?php

namespace App\Actions;

use App\Error\Exceptions\ForbiddenException;
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
    protected bool $rememberDestination = false;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: check access by middleware
        if (!$this->hasAccess($request)) {
            throw new ForbiddenException();
        }
        if ($this->rememberDestination) {
            $this->sessionService->rememberDestination();
        }
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

    protected function hasAccess(ServerRequestInterface $request): bool
    {
        return true;
    }
}