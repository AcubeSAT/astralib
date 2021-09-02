<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\AuthorDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * @method AuthorDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuthorDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuthorDocument[]    findAll()
 * @method AuthorDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorDocumentRepository extends SortableRepository
{
    /**
     * A list of author types that should not be listed as regular authors
     * @var string[]
     */
    private $typeBlockList = [ "copyeditor", "reviewer", "director" ];

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }

    public function isRegularAuthor(AuthorDocument $authorDocument): bool
    {
        return in_array(strtolower($authorDocument->getType()), $this->typeBlockList);
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
