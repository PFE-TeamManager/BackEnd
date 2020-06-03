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
        $teamRepository = $this->entityManager->getRepository(Team::class);
        $teams = $teamRepository->findAllDataTable();
        return $teams;
    }
}
