<?php

namespace App\Actions\Auth;

use App\Actions\AbstractAction;
use App\Router\RouterInterface;
use App\Services\SessionService;
use App\Template\TemplateEngineInterface;
use Psr\Http\Message\ServerRequestInterface;


class LoginFormAction extends AbstractAction
{
    protected string $view = 'auth/form';

    public function __construct(TemplateEngineInterface $templateEngine, RouterInterface $router, SessionService $sessionService)
    {
        $this->setTemplateEngine($templateEngine);
        $this->setRouter($router);
        $this->setSessionService($sessionService);
    }

    protected function getRenderParams(ServerRequestInterface $request): array
    {
        return array_merge(parent::getRenderParams($request), [
            'loginUrl' => $this->generateUrl(LoginAction::class),
        ]);
    }
}