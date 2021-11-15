<?php

namespace App\Actions\Auth;

use App\Actions\AbstractAction;
use App\Actions\HomeAction;
use App\Router\RouterInterface;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Auth\AuthServiceTrait;
use App\Services\SessionService;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class LoginAction extends AbstractAction
{
    use AuthServiceTrait;

    public function __construct(RouterInterface $router, AuthServiceInterface $authService, SessionService $sessionService)
    {
        $this->setRouter($router);
        $this->setAuthService($authService);
        $this->setSessionService($sessionService);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $requestBody = $request->getParsedBody();
            $this->authService->authenticate($requestBody['user'], $requestBody['pass']);
            $this->sessionService->setIsAdmin();
            $this->sessionService->setSuccessFlash('Вход успешно выполнен!');
            return (new Response())
                ->withHeader('Location', $this->generateUrl(HomeAction::class))
                ->withStatus(302);
        } catch (\DomainException $e) {
            $this->sessionService->clearIsAdmin();
            $this->sessionService->setErrorFlash('Введены неверное имя или пароль!');
            return (new Response())
                ->withHeader('Location', $this->generateUrl(LoginFormAction::class))
                ->withStatus(302);

        }
    }
}