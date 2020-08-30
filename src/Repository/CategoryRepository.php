<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllOrderedByLabel()
    {
        // je crée "l'usine" à requete
        $queryBuilder = $this->createQueryBuilder('category');

        // fabrique une requete personnalisée
        $queryBuilder->orderBy('category.label', 'asc');

        // a la fin je recupère a la requete fabriquée
        $query = $queryBuilder->getQuery();

        // j'execute la requete pour en recupérer les resultats
        // getResult me renvoi une LISTE des resultats 
        return $query->getResult();
    }

    public function findOneWithTvShows($id)
    {
        $queryBuilder = $this->createQueryBuilder('category');

        $queryBuilder->where(
            $queryBuilder->expr()->eq('category.id', $id)
        );

        $queryBuilder->leftJoin('category.tvShows', 'tvShow');
        $queryBuilder->addSelect('tvShow');

        $queryBuilder->orderBy('tvShow.title', 'asc');
        
        $query = $queryBuilder->getQuery();
        
        // me renvoi UN seul resultat 
        return $query->getOneOrNullResult();
    }
}
