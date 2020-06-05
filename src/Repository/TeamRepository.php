<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }
    
    public function findAllDataTable(){
        return $this->createQueryBuilder('t')
                    ->select("t.id as idTeam,t.teamName,t.enabled as teamenabled")
                    ->leftJoin('t.project', 'p')
                    ->addSelect('p.id as idProject,p.projectName,p.enabled as projectenabled')
                    ->orderBy('t.createdAt', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function ActivateDeactivateTeam($idTeam,$enabled){
        return $this->createQueryBuilder('t')
                    ->update()
                    ->set('t.enabled', $enabled)
                    ->where('t.id = :id')
                    ->setParameter('id', $idTeam)
                    ->getQuery()
                    ->execute();
    }
}
