<?php

namespace App\Repository;

use App\Entity\TvShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TvShow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TvShow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TvShow[]    findAll()
 * @method TvShow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TvShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TvShow::class);
    }

    // cette méthode doit me renvoyer une series (qui correspond a l'id en parametre)
    // cette série doit contenir les objet liés
    // exemple : SI $id contient la valeur 6
    public function findWithCollections($id)
    {
        // de base ma requete ressemble à : SELECT * FROM tvShow
        $queryBuilder = $this->createQueryBuilder('tvShow');

        // je personnalise ma requete

        // je precise que je souhaite recupérer un element grace a son ID
        $queryBuilder->where(
            $queryBuilder->expr()->eq('tvShow.id', $id)
        );
        // maintenant le query builder va me donner une requete du genre :
        // SELECT * FROM tvShow WHERE tvShow.id = 6

        // je recupére les categories liés a ma serie
        $queryBuilder->leftJoin('tvShow.categories', 'category');
        // j'ajoute aux objets à créer les catégorie
        $queryBuilder->addSelect('category');

        $queryBuilder->leftJoin('tvShow.characters', 'character');
        $queryBuilder->addSelect('character');

        $queryBuilder->leftJoin('character.actors', 'actor');
        $queryBuilder->addSelect('actor');

        // j'ajoute meme des tri
        $queryBuilder->addOrderBy('character.name');
        $queryBuilder->addOrderBy('actor.firstName');

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findByTitle($search)
    {
        $queryBuilder = $this->createQueryBuilder('tvShow');

        $queryBuilder->leftJoin('tvShow.characters', 'character');

        if(!empty($search)) {
            // WHERE tvShow LIKE :search
            $queryBuilder->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('tvShow.title', ':search'),
                    $queryBuilder->expr()->like('character.name', ':search')
                )
            );
            // WHERE tvShow LIKE '%star%'
            $queryBuilder->setParameter('search', "%$search%");
        }

        $queryBuilder->addOrderBy('tvShow.title');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }


}
