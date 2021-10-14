<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function fetchAllAuthors(){
        return $this->createQueryBuilder('authors')
                ->orderBy('authors.id', 'ASC')
                ->getQuery();
    }

    public function getByName(string $name):?Author
    {
        return $this
            ->createQueryBuilder('author')
            ->setParameter('name', $name)
            ->andWhere('author.name = :name')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createOrGetData(string $name):Author
    {
        $author = $this->getByName($name);

        if($author instanceof Author){
            return $author;
        }

        return $this->createAuthor($name);
    }

    public function createAuthor(string $name):Author
    {
        $author = new Author();
        $author->setName($name);

        $this->_em->persist($author);

        return $author;
    }
}
