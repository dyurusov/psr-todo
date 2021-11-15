<?php

namespace App\Actions\TaskActions;

use App\Actions\AbstractAction;
use App\Actions\HomeAction;
use App\Router\RouterInterface;
use App\Services\SessionService;
use App\Services\TaskService;
use App\Services\TaskServiceTrait;
use App\Template\TemplateEngineInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class CreateAction extends AbstractAction
{
    use TaskServiceTrait;

    public function __construct(TemplateEngineInterface $templateEngine, TaskService $taskService, RouterInterface $router, SessionService $sessionService)
    {
        $this->setTemplateEngine($templateEngine);
        $this->setTaskService($taskService);
        $this->setRouter($router);
        $this->setSessionService($sessionService);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = $request->getParsedBody();

        $errors = $this->taskService->validate($requestBody, ['user', 'email', 'description']);
        if ($errors) {
            $this->sessionService->setErrorFlash($errors);
            $this->sessionService->setFormState($requestBody);
            return (new Response())
                ->withHeader('Location', $this->generateUrl(CreateFormAction::class, [
                    'id' => $request->getAttribute('id'),
                ]))
                ->withStatus(302);
        }

        $this->taskService->create($requestBody);

        $this->sessionService->clearFormState();
        $this->sessionService->setSuccessFlash('Задача успешно сохранена!');
        $destination = $this->sessionService->getDestination();
        return (new Response())
            ->withHeader('Location', $destination ?: $this->generateUrl(HomeAction::class))
            ->withStatus(302);
    }
}