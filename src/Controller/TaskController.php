<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController
{   
    #[Route('/tasks')]
    public function task(): Response
    {
        $number = random_int(0, 100);

        return new Response (
            $number
        );
    }
}