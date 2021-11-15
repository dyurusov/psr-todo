<?php

namespace App\Actions\TaskActions;

use App\Actions\AbstractAction;
use App\Router\RouterInterface;
use App\Services\SessionService;
use App\Services\TaskService;
use App\Services\TaskServiceTrait;
use App\Template\TemplateEngineInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateFormAction extends AbstractAction
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
        return array_merge(parent::getRenderParams($request), [
            'task' => $this->sessionService->getFormState() ?: [],
            'isCreating' => true,
        ]);
    }
}