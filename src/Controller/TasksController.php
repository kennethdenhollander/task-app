<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TasksType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


class TasksController extends AbstractController
{
    // https://symfony.com/doc/current/forms.html#rendering-forms

    #[Route('/', name: 'tasks')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        // Getting all tasks
        $tasks = $doctrine->getRepository(Task::class)->findAll();
        
        // Setting new Task object and creating form
        $task = new Task();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        // Check if the form is submitted
        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Get the form data
            $task = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            // Redirect to the homepage that displays all tasks
            return $this->redirectToRoute('tasks');
        }

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasks,
            'form' => $form->createView()
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
}
