<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsersDatableAction
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke()
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        $users = $userRepository->findAllDataTable();
        if( $users ){
            return new JsonResponse(['users' => $users]);
        } else {
            return new JsonResponse(['errors' => 'Something is wrong'], 400);
        }
        
    }
}
