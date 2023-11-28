<?php

namespace App\Repository;

use App\Entity\Kategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kategorie>
 *
 * @method Kategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kategorie[]    findAll()
 * @method Kategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kategorie::class);
    }

}
