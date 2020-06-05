<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TeamsDatableAction
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
        $teamRepository = $this->entityManager->getRepository(Team::class);
        $teams = $teamRepository->findAllDataTable();

        foreach( $teams as $key => $team ){
            $arrayReturn[$key]["idTeam"] = $team["idTeam"];
            $arrayReturn[$key]["teamName"] = $team["teamName"];
            if( $team["teamenabled"] ){
                $arrayReturn[$key]["teamenabled"] = "Active";
            } else {
                $arrayReturn[$key]["teamenabled"] = "Non Active";
            }
            $arrayReturn[$key]["idProject"] = $team["idProject"];
            $arrayReturn[$key]["projectName"] = $team["projectName"];
            $arrayReturn[$key]["projectenabled"] = $team["projectenabled"];
        }

        return $arrayReturn;
    }
}
