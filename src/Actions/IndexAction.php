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
    protected string $sortedColumnParam = 'sortedColumn';
    protected string $sortDirectionParam = 'sortDirection';

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
                'pager' => $this->getPagerData($page, $maxPage, $sort),
                'columns' => $this->getColumnsData($sort),
                'isAdmin' => false,
            ]));
        return $response;
    }

    protected function parseQueryParams(array $queryParams): array
    {
        $limit = $queryParams[$this->limitParam] ?? $this->defaultLimit;

        $page = $queryParams[$this->pageParam] ?? $this->defaultPage;
        $maxPage = ceil($this->taskService->count() / $limit) - 1;
        if ($page < 0) {
            $page = 0;
        }
        if ($page > $maxPage) {
            $page = $maxPage;
        }

        $sort = [];
        $sortedColumn = $queryParams[$this->sortedColumnParam] ?? null;
        $sortDirection = $queryParams[$this->sortDirectionParam] ?? null;
        if ($sortedColumn && $sortDirection) {
            $sort[$sortedColumn] = ($sortDirection > 0);
        }

        return [$limit, $page, $sort, $maxPage];
    }

    protected function getPagerData($page, $maxPage, $sort): array
    {
        $pager = [];
        $pager[] = [
            'disabled' => ($page <= 0),
            'label' => '&laquo;',
            'href' => $this->generatePageUrl($page - 1, $sort),
        ];
        for ($i = 0; $i <= $maxPage; $i++) {
            $pager[] = [
                'active' => ($i == $page),
                'label' => $i + 1,
                'href' => $this->generatePageUrl($i, $sort),
            ];
        }
        $pager[] = [
            'disabled' => ($page >= $maxPage),
            'label' => '&raquo;',
            'href' => $this->generatePageUrl($page + 1, $sort),
        ];
        return $pager;
    }

    protected function getColumnsData(array $sort): array
    {
        $columns = [];
        foreach (['user', 'email', 'done'] as $column) {
            $isSorted = array_key_exists($column, $sort);
            $sortDirection = $isSorted ? $sort[$column] : null;
            $columns[$column] = [
                'isSorted' => $isSorted,
                'sortDirection' => $sortDirection,
                'href' => $this->generateUrl(static::class, [], [
                    $this->sortedColumnParam => $column,
                    $this->sortDirectionParam => ($isSorted && $sortDirection) ? -1 : 1,
                ]),
            ];
        }
        return $columns;
    }

    protected function generatePageUrl(int $page, $sort): string
    {
        $queryParams = [];
        if ($page !== $this->defaultPage) {
            $queryParams[$this->pageParam] = $page;
        }
        $sortedColumn = array_keys($sort);
        if (!empty($sortedColumn)) {
            $queryParams[$this->sortedColumnParam] = $sortedColumn[0];
            $queryParams[$this->sortDirectionParam] = $sort[$sortedColumn[0]] ? 1 : -1;
        }
        return $this->generateUrl(static::class, [], $queryParams);
    }
}