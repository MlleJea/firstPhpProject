<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskService
{
    public function __construct(private TaskRepository $taskRepository) {}


    public function indexTasks(): array
    {

        return $this->taskRepository->findAll();
    }

    public function createTask(array $data): Task
    {

        $task = new Task();
        $task->setTitle($data['title'] ?? '');
        $task->setDescription($data['description'] ?? null);

        $this->taskRepository->save($task);

        return $task;
    }

    public function updateTasks(int $id, array $data): Task
    {
        $task = $this->getTaskById($id);

        $task->setTitle($data['title'] ?? $task->getTitle());
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setCompleted($data['completed'] ?? $task->isCompleted());

        $this->taskRepository->save($task);

        return $task;
    }

    public function deleteTask(int $id): void
    {
        $task = $this->getTaskById($id);
        $this->taskRepository->delete($task);
    }


    public function getTaskById(int $id): Task
    {

        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }
        return $task;
    }
}
