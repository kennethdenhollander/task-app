<?php

namespace App\Controller;

use App\Service\AdminUsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(AdminUsersService $users): Response
    {
        $users = $users->getUsers();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
}
