<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    public function __construct(private ArticleRepository $articleRepository)
    {
        $this->articleRepository = $this->articleRepository;
    }

    #[Route('/count', name: 'count')]
    public function count()
    {
        return new Response($this->articleRepository->count([])) ;
    }

    public function  __invoke(Article $article):Article{
        return $article->setOnline(true);
    }

/*    #[Route('/count', name: 'count', methods: ['GET'])]
    public function count()
    {
        var_dump("test var");die();
        return $this->json("test",Response::HTTP_OK);
    }*/
}
