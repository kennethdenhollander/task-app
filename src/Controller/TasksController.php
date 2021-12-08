<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    #[Route('/', name: 'tasks')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $tasks = $doctrine->getRepository(Task::class)->findAll();

        return $this->render('tasks/task-index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    // Making a route where the information of task is shown
    #[Route('/tasks/{id}', name: 'task_show')]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $task = $doctrine->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('tasks/task-single.html.twig', [
            'task' => $task,
        ]);
    }

    // Creating a route to create a task
    #[Route('/register-task', name: 'create_task')]
    public function createTask(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        // Input should be replaced with user input
        $task = new Task();
        $task->setName('Stofzuigen');
        $task->setDescription('Woonkamer, Badkamer & Keuken');

        $entityManager->persist($task);
        $entityManager->flush();

        return new Response('Saved new task with id ' . $task->getId());
    }
}
