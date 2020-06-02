<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllDataTable(){
        return $this->createQueryBuilder('u')
                    ->select("u.id as idMember,u.username,u.email,u.roles,u.enabled as userenabled,DATE_FORMAT(u.date_embauchement, '%Y-%m-%d') as dateembauchement")
                    ->leftJoin('u.teams', 't')
                    ->addSelect('t.id as idTeam,t.teamName,t.enabled as teamenabled')
                    ->where("u.roles NOT LIKE :role1")
                    ->andWhere("u.roles NOT LIKE :role2")
                    ->setParameters(array('role1'=> "%ROLE_CHEF_PROJET%", 'role2' => "%ROLE_ADMIN%"))
                    ->orderBy('u.createdAt', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

}
