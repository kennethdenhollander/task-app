<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    #[Route('/', name: 'tasks')]
    public function index(): Response
    {
        $tasks = [
            '1' => 'Applicatie maken',
            '2' => 'Vaatwasser leeghalen',
            '3' => 'Prullenbak legen'
        ];

        return $this->render('tasks/task-index.html.twig', [
            'tasks' => $tasks
        ]);
    }
}
