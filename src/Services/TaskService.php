<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;


class TaskService
{
    private TaskRepositoryInterface $repo;

    public function __construct(TaskRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getMany(int $offset = 0, int $limit = 0, array $sort = ['id' => true]): array {
        return array_map([$this, 'mapper'], $this->repo->getMany($offset, $limit, $sort));
    }

    public function count(): int
    {
        return $this->repo->count();
    }

    private function mapper(Task $task): array {
        return $task->getProps();
    }
}