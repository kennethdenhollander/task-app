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

        return new RedirectResponse($this->generateUrl('tasks'));
    }

    // Page to create a task
    #[Route('/create', name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
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

        return $this->renderForm('tasks/new.html.twig', [
            'form' => $form,
        ]);
    }
}
