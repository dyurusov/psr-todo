<?php

namespace App\Repositories;

interface TaskRepositoryInterface
{
    public function getMany(int $offset, int $limit, array $sort): array;
    public function count(): int;
}
