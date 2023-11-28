<?php

namespace App\Repository;

use App\Entity\Recept;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recept>
 *
 * @method Recept|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recept|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recept[]    findAll()
 * @method Recept[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recept::class);
    }

}
