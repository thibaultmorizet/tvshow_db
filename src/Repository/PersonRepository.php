<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    // /**
    //  * @return Person[] Returns an array of Person objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllOrderedByFirstName()
    {
        // je crée "l'usine" à requete
        $queryBuilder = $this->createQueryBuilder('person');

        // fabrique une requete personnalisée
        $queryBuilder->orderBy('person.firstName', 'asc');

        // a la fin je recupère a la requete fabriquée
        $query = $queryBuilder->getQuery();

        // j'execute la requete pour en recupérer les resultats
        // getResult me renvoi une LISTE des resultats 
        return $query->getResult();
    }

    public function findWithCollections($id)
    {
        // de base ma requete ressemble à : SELECT * FROM tvShow
        $queryBuilder = $this->createQueryBuilder('person');

        // je personnalise ma requete

        // je precise que je souhaite recupérer un element grace a son ID
        $queryBuilder->where(
            $queryBuilder->expr()->eq('person.id', $id)
        );
        // maintenant le query builder va me donner une requete du genre :
        // SELECT * FROM tvShow WHERE tvShow.id = 6
        $queryBuilder->addOrderBy('person.firstName');

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }
}
