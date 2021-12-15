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

    #[Route('/tasks', name: 'tasks')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {   
        // Getting the current userID
        $userId = $this->getUser()->getId();

        // Getting all tasks
        $tasks = $doctrine->getRepository(Task::class)->findBy(
            ['creator' => $userId]
        );
        
        // Setting new Task object and creating form
        $task = new Task();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        // Check if the form is submitted
        if ($form->isSubmitted() && $form->isValid()) 
        {   

            $task = $form->getData();
            $task->setByUserId($userId);

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

    #[Route ('/tasks/edit/{id}', name: 'edit_task')]
    public function edit(ManagerRegistry $doctrine, int $id = null, Request $request): Response
    {       
        $task = new Task();
        $userId = $this->getUser()->getId();
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->findOneBy(
            array(
                'id' => $id,
                'creator' => $userId
            )
        );

        if (empty($task) || $task == null)
        {
            throw $this->createNotFoundException(
                'No task found for id ' . $id
            );
        }

        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        // Check if the form is submitted
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $complete = $form['complete']->getData();

            $task->setName($name);
            $task->setDescription($description);
            $task->setComplete($complete);
            $entityManager->flush();

            // Redirect to the homepage that displays all tasks
            return $this->redirectToRoute('tasks');
        }

        return $this->render('tasks/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }

    // Route for deleting tasks
    #[Route('/tasks/remove/{id}', name: 'remove_task')]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {   
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if ($task)
        {
            $entityManager->remove($task);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }

        // Throws an exception when the id is not found
        throw $this->createNotFoundException(
            'No task found for id ' . $id
        );
    }

    #[Route('/tasks/complete/{id}', name: 'complete_task')]
    public function complete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if ($task)
        {
            $task->setComplete(true);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }

        // Throws an exception when the id is not found
        throw $this->createNotFoundException(
            'No task found for id ' . $id
        );
    }

    // Route for 'One' view
    #[Route('/tasks/{id}', name: 'task_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $task = $doctrine->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return $this->render('tasks/task-single.html.twig', [
            'task' => $task,
        ]);
    }


}
