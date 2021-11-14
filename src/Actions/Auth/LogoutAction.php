<?php

namespace App\Actions\Auth;

use App\Actions\AbstractAction;
use App\Actions\HomeAction;
use App\Router\RouterInterface;
use App\Services\SessionService;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class LogoutAction extends AbstractAction
{
    public function __construct(RouterInterface $router, SessionService $sessionService)
    {
        $this->setRouter($router);
        $this->setSessionService($sessionService);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->sessionService->clearIsAdmin();
        $this->sessionService->setSuccessFlash('Выход успешно выполнен!');
        return (new Response())
            ->withHeader('Location', $this->generateUrl(HomeAction::class))
            ->withStatus(302);
    }
}