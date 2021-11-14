<?php

namespace App\Actions;

use App\Router\RouterInterface;
use App\Router\RouterTrait;
use App\Services\TaskService;
use App\Services\TaskServiceTrait;
use App\Template\TemplateEngineInterface;
use App\Template\TemplateTrait;
use Laminas\Diactoros\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class IndexAction implements RequestHandlerInterface
{
    use TemplateTrait;
    use TaskServiceTrait;
    use RouterTrait;

    protected string $pageParam = 'page';
    protected string $limitParam = 'perPage';
    protected int $defaultLimit = 3;
    protected int $defaultPage = 0;

    public function __construct(TemplateEngineInterface $templateEngine, TaskService $taskService, RouterInterface $router)
    {
        $this->setTemplateEngine($templateEngine);
        $this->setTaskService($taskService);
        $this->setRouter($router);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        list ($limit, $page, $sort, $maxPage) = $this->parseQueryParams($request->getQueryParams());
        $response = new Response();
        $response->getBody()
            ->write($this->render('index', [
                'tasks' => $this->taskService->getMany($page * $limit, $limit, $sort),
                'pager' => $this->getPagerData($page, $maxPage),
                'isAdmin' => false,
            ]));
        return $response;
    }

    protected function parseQueryParams(array $queryParams): array
    {
        $limit = $queryParams[$this->limitParam] ?? $this->defaultLimit;
        $page = $queryParams[$this->pageParam] ?? $this->defaultPage;
        if ($page < 0) {
            $page = 0;
        }
        if ($page < 0) {
            $page = 0;
        }
        $maxPage = ceil($this->taskService->count() / $limit) - 1;
        if ($page > $maxPage) {
            $page = $maxPage;
        }
        $sort = [];
        return [$limit, $page, $sort, $maxPage];
    }

    protected function getPagerData($page, $maxPage): array
    {
        $pager = [];
        $pager[] = [
            'disabled' => ($page <= 0),
            'label' => '&laquo;',
            'href' => $this->generatePageUrl($page - 1),
        ];
        for ($i = 0; $i <= $maxPage; $i++) {
            $pager[] = [
                'active' => ($i == $page),
                'label' => $i + 1,
                'href' => $this->generatePageUrl($i),
            ];
        }
        $pager[] = [
            'disabled' => ($page >= $maxPage),
            'label' => '&raquo;',
            'href' => $this->generatePageUrl($page + 1),
        ];
        return $pager;
    }

    protected function generatePageUrl(int $page): string
    {
        return $this->generateUrl(static::class, [], ($page !== $this->defaultPage) ? [ $this->pageParam => $page] : []);
    }
}