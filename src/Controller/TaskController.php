<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tasks', name: 'api_tasks_')]
class TaskController extends AbstractController
{
    /** Test test */

    public function __construct(
        private TaskService $taskService
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {

        $tasks = $this->taskService->indexTasks();
        $data = array_map(fn(Task $task) => [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'completed' => $task->isCompleted(),
            'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
        ], $tasks);
        return $this->json($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $taskService = $this->taskService;
        $task = $taskService->createTask($data);

        return $this->json([
            'id' => $task->getId(),
            'message' => 'Task created successfully'
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $task = $this->taskService->updateTasks($id, $data);

            return $this->json(['message' => 'Task updated successfully']);
        } catch (NotFoundHttpException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->taskService->deleteTask($id);
            return $this->json(['message' => 'Task deleted successfully']);
        } catch (NotFoundHttpException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
