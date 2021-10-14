<?php

namespace App\Controller\Article;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article", name="article")
 */
class CreateDataController extends AbstractController
{

    /**
     * @Route("/create", name="article_add")
     */
    public function createData(
        ArticleRepository $articleRepository,
        Request $request,
        EntityManagerInterface $entityManager
    )
    : Response
    {
        $bag = $request->isMethod(Request::METHOD_POST) ? $request->request : $request->query;
        $name = $bag->get('name');
        $authors = $bag->get('authors');
        $publishAt = $bag->get('publishAt');
        $article = $articleRepository->createOfGotData($name, $authors, $publishAt);
        $entityManager->flush();

        return new JsonResponse(
            [
                'article'=> $article->getName(),
                'publish_at' => $article->getPublishAt()
            ]
        );
    }
}
