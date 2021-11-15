<?php

namespace App\Actions\TaskActions;

use App\Actions\AbstractAction;
use App\Actions\HomeAction;
use App\Error\Exceptions\ForbiddenException;
use App\Error\Exceptions\NotFoundException;
use App\Router\RouterInterface;
use App\Services\SessionService;
use App\Services\TaskService;
use App\Services\TaskServiceTrait;
use App\Template\TemplateEngineInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class UpdateAction extends AbstractAction
{
    use TaskServiceTrait;

    protected string $view = 'tasks/form';

    public function __construct(TemplateEngineInterface $templateEngine, TaskService $taskService, RouterInterface $router, SessionService $sessionService)
    {
        $this->setTemplateEngine($templateEngine);
        $this->setTaskService($taskService);
        $this->setRouter($router);
        $this->setSessionService($sessionService);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->hasAccess($request)) {
            throw new ForbiddenException();
        }

        $requestBody = $request->getParsedBody();

        $errors = $this->taskService->validate($requestBody, ['description']);
        if ($errors) {
            $this->sessionService->setErrorFlash($errors);
            $this->sessionService->setFormState($requestBody);
            return (new Response())
                ->withHeader('Location', $this->generateUrl(UpdateFormAction::class, [
                    'id' => $request->getAttribute('id'),
                ]))
                ->withStatus(302);
        }

        $task = $this->taskService->getOne($request->getAttribute('id', null));
        if (!$task) {
            throw new NotFoundException();
        }

        $data = $this->taskService->purify($requestBody);
        if (!$task['edited'] && ($task['description'] !== $data['description'])) {
            $task['edited'] = true;
        }
        $task['description'] = $data['description'];
        $task['done'] = $data['done'];
        $this->taskService->save($task);

        $this->sessionService->clearFormState();
        $this->sessionService->setSuccessFlash('Задача успешно сохранена!');
        $destination = $this->sessionService->getDestination();
        return (new Response())
            ->withHeader('Location', $destination ?: $this->generateUrl(HomeAction::class))
            ->withStatus(302);
    }

    protected function hasAccess(ServerRequestInterface $request): bool
    {
        return $this->sessionService->getIsAdmin();
    }
}