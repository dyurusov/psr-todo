<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    use DbConnectionTrait;

    public function __construct(\PDO $dbConnection)
    {
        $this->setDbConnection($dbConnection);
    }

    public function getMany(int $offset, int $limit, array $sort): array
    {
        $result = [];
        $sql = $this->_composeManySql($offset, $limit, $sort);
        foreach ($this->dbConnection->query($sql) as $record) {
            $result[] = new Task($record);
        }
        return $result;
    }

    public function count(): int
    {
        return $this->dbConnection->query("SELECT COUNT(*) FROM Tasks")->fetchColumn();
    }

    private function _composeManySql(int $offset, int $limit, array $sort): string
    {
        $sql = "SELECT * FROM Tasks";
        if (!empty($sort)) {
            $order = [];
            foreach ($sort as $filed => $isAsc) {
                $order[] = $filed . ' ' . ($isAsc ? 'ASC' : 'DESC');
            }
            $sql .= ' ORDER BY ' . implode(',', $order);
        }
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        if ($offset) {
            $sql .= " OFFSET $offset";
        }
        return $sql;
    }
}