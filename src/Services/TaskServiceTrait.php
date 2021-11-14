<?php

namespace App\Services;

trait TaskServiceTrait
{
    protected TaskService $taskService;

    protected function setTaskService(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
}