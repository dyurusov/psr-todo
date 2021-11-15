<?php

namespace App\Actions\TaskActions;

use App\Actions\AbstractAction;
use App\Error\Exceptions\NotFoundException;
use App\Router\RouterInterface;
use App\Services\SessionService;
use App\Services\TaskService;
use App\Services\TaskServiceTrait;
use App\Template\TemplateEngineInterface;
use Psr\Http\Message\ServerRequestInterface;

class UpdateFormAction extends AbstractAction
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

    protected function getRenderParams(ServerRequestInterface $request): array
    {
        $task = $this->sessionService->getFormState()
            ?: $this->taskService->getOne($request->getAttribute('id', null));

        if (!$task) {
            throw new NotFoundException();
        }
        return array_merge(parent::getRenderParams($request), [
            'task' => $task,
        ]);
    }

    protected function hasAccess(ServerRequestInterface $request): bool
    {
        return $this->sessionService->getIsAdmin();
    }
}