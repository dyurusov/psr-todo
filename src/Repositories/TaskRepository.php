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

    public function save(Task $task): bool
    {
        $sql = $this->getOne($task->id)
            ? "
                UPDATE Tasks
                SET
                    user=:user,
                    email=:email,
                    description=:description,
                    edited=:edited,
                    done=:done
                WHERE
                    id=:id
            "
            : "
                INSERT INTO Tasks
                    (id, user, email, description, edited, done)
                VALUES
                    (:id, :user, :email, :description, :edited, :done)
            ";
        return $this->dbConnection->prepare($sql)->execute([
            'id' => $task->id,
            'user' => $task->user,
            'email' => $task->email,
            'description' => $task->description,
            'edited' => $task->edited,
            'done' => $task->done,
        ]);
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