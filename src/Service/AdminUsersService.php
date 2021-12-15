<?php


namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class AdminUsersService 
{
    private $users;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->users = $doctrine->getRepository(User::class)->findAll();
    }

    public function getUsers(): array
    {
        $users = $this->users;
        $userList = [];

        foreach ($users as $user)
        {
            array_push($userList, $user->getUserIdentifier());
        }

        return $userList;
    }
}