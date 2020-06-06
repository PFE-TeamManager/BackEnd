<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamsAction
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
        dd($request);
        dd($request->get("id"));
        dd($request->get("name"));
        $arrayReturn = [];
        $teamRepository = $this->entityManager->getRepository(Team::class);

        $teams = $teamRepository->EditTeamName($request->get("id"),$request->get("name"));
        return new JsonResponse(['New Team Name' => $request->get("name")]);

    }
}
