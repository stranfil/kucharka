<?php

namespace App\Repository;

use App\Entity\Nastroj;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nastroj>
 *
 * @method Nastroj|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nastroj|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nastroj[]    findAll()
 * @method Nastroj[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NastrojRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nastroj::class);
    }

}
