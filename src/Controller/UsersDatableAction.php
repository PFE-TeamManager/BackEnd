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
        $arrayReturn = [];
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAllDataTable();

        foreach( $users as $key => $user ){
            $arrayReturn[$key]["idMember"] = $user["idMember"];
            $arrayReturn[$key]["username"] = $user["username"];
            $arrayReturn[$key]["email"] = $user["email"];
            if( in_array("ROLE_DEV",$user["roles"]) ){
                $arrayReturn[$key]["roles"] = "Développeur";
            } else {
                $arrayReturn[$key]["roles"] = "Membre";
            }
            //$arrayReturn[$key]["userenabled"] = $user["userenabled"];
            $arrayReturn[$key]["dateembauchement"] = $user["dateembauchement"];
            $arrayReturn[$key]["idTeam"] = $user["idTeam"];
            $arrayReturn[$key]["teamName"] = $user["teamName"];
            $arrayReturn[$key]["teamenabled"] = $user["teamenabled"];
        }

        return $arrayReturn;
    }
}
