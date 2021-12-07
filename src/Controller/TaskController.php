<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{   
    #[Route('/tasks', name: 'task_index')]
    public function taskIndex(): Response
    {
        $number = random_int(0, 100);
        return $this->render('tasks/task-index.html.twig', [
            'number' => $number,
        ]);
    }

    #[Route('/tasks/{slug}', name: 'task_single')]
    public function singleTask(string $slug): Response
    {
        return $this->render('tasks/task-single.html.twig', [
            'slug' => $slug,
        ]);
    }

    #[Route('/register-task', name: 'register_task')]
    public function registerTask(): Response
    {
        $number = random_int(0, 100);
        return $this->render('tasks/register-task.html.twig', [
            'number' => $number,
        ]);
    }
}