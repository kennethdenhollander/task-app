<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{   
    #[Route('/tasks')]
    public function task(): Response
    {
        $number = random_int(0, 100);
        return $this->render('tasks/task.html.twig', [
            'number' => $number,
        ]);
    }
}