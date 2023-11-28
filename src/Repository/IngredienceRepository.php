<?php

namespace App\Repository;

use App\Entity\Ingredience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredience>
 *
 * @method Ingredience|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredience|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredience[]    findAll()
 * @method Ingredience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method array<int, int|string>   findReceptyByIngredienceName(string $name)
 */
class IngredienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredience::class);
    }

    /**
     * @return array<int, int|string>
     */
    public function findReceptyByIngredienceName(string $name): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT ri.recept_id FROM ingredience AS i
            JOIN recept_ingredience AS ri ON i.id = ri.ingredience_id
            WHERE i.name = :name
            ';

        $resultSet = $conn->executeQuery($sql, ['name' => $name]);

        // returns an array of recept_ids
        return array_keys($resultSet->fetchAllAssociativeIndexed());
    }

}
