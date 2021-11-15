<?php

namespace App\Repositories;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function getMany(int $offset, int $limit, array $sort): array;
    public function count(): int;
    public function getOne($id);
    public function save(Task $task);
}
