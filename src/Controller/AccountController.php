<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(): Response
    {
        // Symfony Serializer
        $user = $this->getUser();
        $email = $user->getUserIdentifier();

        return $this->render('account/index.html.twig', [
            'email' => $email,
        ]);
    }
}
