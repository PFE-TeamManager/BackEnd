<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamsActivityAction
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

    public function __invoke(Request $request)
    {
        //dd($request);
        $arrayReturn = [];
        $teamRepository = $this->entityManager->getRepository(Team::class);
        $teamInfo = $teamRepository->findOneBy(array('id' => $request->get("id")));
        $enabledTeam = $teamInfo->getEnabled();

        if( $enabledTeam === true){
            $teams = $teamRepository->ActivateDeactivateTeam($request->get("id"),0);
            return new JsonResponse(['activity' => "non active"]);
        }

        if( $enabledTeam === false){
            $teams = $teamRepository->ActivateDeactivateTeam($request->get("id"),1);
            return new JsonResponse(['activity' => "active"]);
        }

    }
}
