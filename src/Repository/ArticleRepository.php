<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //Recherche les articles par année
    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findByYear($year)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.dateCreation like :year')
            ->setParameter('year', $year.'%')
            ->orderBy('a.dateCreation', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    //Recherche les articles par contenu
    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findByContent($content)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.contenu like :content')
            ->setParameter('content', '%'. $content.'%')
            ->orderBy('a.dateCreation', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }



    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findAllYears($value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.dateCreation like :year')
            ->setParameter('year', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
