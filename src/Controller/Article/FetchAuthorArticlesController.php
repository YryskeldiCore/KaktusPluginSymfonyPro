<?php

namespace App\Controller\Article;

use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fetch', name: 'fetch')]
class FetchAuthorArticlesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;

    /**
     * @var AuthorRepository
     */
    private AuthorRepository $authorRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository,
        AuthorRepository $authorRepository
    ){
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
        $this->authorRepository = $authorRepository;
    }

    #[Route('/author/{author_id}', name: '_one')]
    public function fetchAuthorArticles($author_id): Response
    {

//        $articles = $this->articleRepository->findBy(['author_id' => $author_id]);
        $author = $this->authorRepository->findOneBy(['id' => $author_id]);
        $articles = $this->articleRepository->fetchByAuthor($author_id);
        dump($articles);
        dump($author);

        return $this->render('fetch_author_articles/index.html.twig', [
            'articles' => $articles,
            'author' => $author
        ]);
    }
}
