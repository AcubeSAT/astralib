<?php

namespace App\Repository;

use App\Entity\AuthorDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuthorDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthorDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthorDocument[]    findAll()
 * @method AuthorDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthorDocument::class);
    }

    // /**
    //  * @return AuthorDocument[] Returns an array of AuthorDocument objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AuthorDocument
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
