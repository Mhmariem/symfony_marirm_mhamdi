<?php

namespace App\Repository;
use app\Form\BookType;
use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
    public function searchByRef($ref)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.ref LIKE :ref')
            ->setParameter('ref', '%'.$ref.'%')
            ->getQuery()
            ->getResult();
    }
    public function findAllByOrderByusernameAuthor()
    {
        return $this->createQueryBuilder('b')
             ->join('b.author', 'a')
            ->orderBy('a.username', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findBooksPublishedBeforeYearWithAuthorMoreThan35Books($year)
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->where('b.publicationdate < :year')
            ->andWhere('a.nb_Book > 35')
            ->setParameter('year', $year . '-01-01')
            ->getQuery()
            ->getResult();
    }
    public function countPublishedBooks()
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b)')
            ->where('b.published = :published')
            ->setParameter('published', true)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function countUnpublishedBooks()
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b)')
            ->where('b.published = :published')
            ->setParameter('published', false)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function sumScienceFictionBooks()
    {
        return $this->createQueryBuilder('b')
            ->select('SUM(CASE WHEN b.category = :category THEN 1 ELSE 0 END) as sumScienceFiction')
            ->setParameter('category', 'Science Fiction')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
