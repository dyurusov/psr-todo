<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;


class TaskService
{
    use ValidationServiceTrait;

    private TaskRepositoryInterface $repo;

    public function __construct(TaskRepositoryInterface $repo, ValidationService $validationService)
    {
        $this->repo = $repo;
        $this->setValidationService($validationService);
    }

    public function getMany(int $offset = 0, int $limit = 0, array $sort = ['id' => true]): array {
        return array_map([$this, 'mapper'], $this->repo->getMany($offset, $limit, $sort));
    }

    public function getOne($id) {
        return $this->mapper($this->repo->getOne($id));
    }

    public function save(array $data)
    {
        $data = $this->purify($data);
        $errors = $this->validate($data);
        if (!empty($errors)) {
            throw new \DomainException(json_encode($errors));
        }
        $task = new Task($data);
        $this->repo->save($task);
    }

    public function validate(array $data, array $propsToValidate = null): array
    {
        $errors = [];

        if (!$propsToValidate || in_array('id', $propsToValidate)) {
            $id = $data['id'] ?? null;
            if ($id === null) {
                $errors[] = 'Нет идентификатора';
            }
        }

        $errorMessages = [
            'user' => 'Пустое имя пользователя',
            'email' => 'Пустой e-mail',
            'description' => 'Пустое описание',
        ];
        foreach ($errorMessages as $prop => $errorMessage) {
            if (!$propsToValidate || in_array($prop, $propsToValidate)) {
                if ($this->validationService->isEmptyString($data[$prop] ?? '')) {
                    $errors[] = $errorMessage;
                }
            }
        }

        if (!$propsToValidate || in_array('email', $propsToValidate)) {
            if (!$this->validationService->isEmail($data['email'] ?? '')) {
                $errors[] = 'Не верный email';
            }
        }

        return $errors;
    }

    public function purify(array $data): array
    {
        $purified = [
            'id' => $data['id'] ?? null,
            'edited' => !!($data['edited'] ?? false),
            'done' => !!($data['done'] ?? false),
        ];
        foreach ([ 'user', 'email', 'description' ] as $prop) {
            $purified[$prop] = trim($data[$prop] ?? '');
        }
        return $purified;
    }

    public function count(): int
    {
        return $this->repo->count();
    }

    private function mapper(Task $task): array {
        return $task->getProps();
    }
}