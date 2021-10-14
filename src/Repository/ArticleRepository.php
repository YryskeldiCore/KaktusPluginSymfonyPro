<?php

namespace App\Repository;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(ManagerRegistry $registry, AuthorRepository $authorRepository)
    {
        parent::__construct($registry, Article::class);
        $this->authorRepository = $authorRepository;
    }

    public function fetchAllArticles(){
        return $this->createQueryBuilder('articles')
                ->orderBy('articles.id', 'ASC')
                ->getQuery();
    }

//    public function deleteAll(){
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = 'DROP TABLE article,author';
//
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();
//
//        return $stmt;
//    }
//
//    public function createTableArticleAgain(){
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = 'CREATE TABLE article(
//                    id INT(10) AUTO_INCREMENT PRIMARY_KEY
//                    name VARCHAR(255) NOT NULL,
//                    publishAt VARCHAR(255) NOT NULL
//                )';
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();
//
//        return $stmt;
//    }
//
//    public function createTableAuthorAgain(){
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = 'CREATE TABLE author(
//                    id INT(10) AUTO_INCREMENT PRIMARY_KEY
//                    name VARCHAR(255) NOT NULL
//                )';
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();
//
//        return $stmt;
//    }

//    public function fetchAllData(){
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = '
//            SELECT * FROM article
//        ';
//
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();
//
//        return $stmt->fetchAllAssociativeIndexed();
//    }

//    public function fetchByAuthor(int $author_id)
//    {
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = '
//            SELECT * FROM article a
//                JOIN article_author aa ON aa.article_id = a.id
//                JOIN author at ON aa.author_id = at.id
//                WHERE author_id = 2;
//        ';
//
//
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();
//
//        return $stmt->fetchAllKeyValue();
//    }

    public function fetchByAuthor(int $author_id){
        return $this->createQueryBuilder('articles')
            ->andWhere('authors.id = :author_id')
            ->setParameter('author_id', $author_id)
            ->leftJoin('articles.authors', 'authors')
            ->getQuery()
            ->getResult();
    }

    //    public function fetchAllData(){
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = '
//            SELECT * FROM article
//        ';
//
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();
//
//        return $stmt->fetchAllAssociativeIndexed();
//    }

    public function getByName(string $name):?Article
    {
        return $this->createQueryBuilder('article')
            ->andWhere('article.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createOfGotData(string $name, array $authors, string $publishAt):Article
    {
        $article = $this->getByName($name);

        if($article instanceof Article){
            return $article;
        }

        return $this->createArticle($name, $authors, $publishAt);
    }

    public function createArticle(string $name, array $authors, string $publishAt):Article
    {
        $article = new Article();
        $article->setName($name);
        $article->setPublishAt($publishAt);

        foreach ($authors as $authorName) {
            $author = $this->authorRepository->createOrGetData($authorName);
            $article->addAuthor($author);
        }

        $this->_em->persist($article);

        return $article;
    }
}
