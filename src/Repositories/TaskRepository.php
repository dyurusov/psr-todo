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

    public function getOne($id)
    {
        $query = $this->dbConnection
            ->prepare('SELECT * FROM Tasks WHERE id=:id');
        $query->bindValue(':id', $id);
        $query->execute();
        $record = $query->fetch(\PDO::FETCH_ASSOC);
        return $record ? (new Task($record)) : null;
    }

    public function count(): int
    {
        return $this->dbConnection->query("SELECT COUNT(*) FROM Tasks")->fetchColumn();
    }

    public function save(Task $task)
    {
        $query = $this->dbConnection->prepare("
            UPDATE Tasks
            SET
                user=:user,
                email=:email,
                description=:description,
                edited=:edited,
                done=:done
            WHERE
                id=:id
        ");
        $query->bindValue(':id', $task->id);
        $query->bindValue(':user', $task->user);
        $query->bindValue(':email', $task->email);
        $query->bindValue(':description', $task->description);
        $query->bindValue(':edited', $task->edited);
        $query->bindValue(':done', $task->done);
        $query->execute();
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