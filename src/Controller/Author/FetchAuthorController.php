<?php

namespace App\Controller\Author;

use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author', name: 'fetch')]
class FetchAuthorController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var AuthorRepository
     */
    private AuthorRepository $authorRepository;

    public function __construct(EntityManagerInterface $entityManager, AuthorRepository $authorRepository){
        $this->entityManager = $entityManager;
        $this->authorRepository = $authorRepository;
    }

    #[Route('/fetch', name: '_author')]
    public function fetchAuthors(Request $request, PaginatorInterface $paginator): Response
    {
//        $authors = $this->authorRepository->findAll();

        $queryBuilder = $this->authorRepository->fetchAllAuthors();

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('fetchauthor/index.html.twig', [
            'pagination'=> $pagination
        ]);
    }
}
