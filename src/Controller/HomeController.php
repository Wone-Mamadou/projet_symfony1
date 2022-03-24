<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $repoArticle;

    public function __construct(ArticleRepository $repoArticle)
    {
        $this->repoArticle = $repoArticle;
    }

    #[Route('/home', name: 'app_home')]
    public function index(CategorieRepository $repoCategorie): Response
    {
        $categories = $repoCategorie->findAll();
        $articles = $this->repoArticle->findAll();

        return $this->render("home/index.html.twig",[
            'articles' => $articles,
            'categories' => $categories
            ] );
    }

    #[Route('/show/{id}', name: 'show')]
    public function show(Article $article): Response
    { 

        //$article = $this->repoArticle->find($id);
        if (!$article) {
            return $this->redirectToRoute('home');
        }
        
        return $this->render("show/index.html.twig",[
            'article' => $article,] );
    }

    #[Route('/showArticle/{id}', name: 'show_article')]
    public function showAricle(?Categorie $categorie): Response
    { 
        if ($categorie) {
            $articles = $categorie->getArticles()->getValues();
        }else {
            
           return $this->redirectToRoute('home');
        }
        
        // dd($articles);

        return $this->render("show/showArticle.html.twig",[
            'articles' => $articles,
        ]);
    }
}
