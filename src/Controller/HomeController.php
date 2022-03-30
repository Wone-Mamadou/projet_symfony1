<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $repoArticle;
    private $repoCategorie;

    public function __construct(ArticleRepository $repoArticle,CategorieRepository $repoCategorie)
    {
        $this->repoArticle = $repoArticle;
        $this->repoCategorie = $repoCategorie;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $categories = $this->repoCategorie->findAll();
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

        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);

        
        return $this->render("show/index.html.twig",[
            'form' =>$form->createView(),
            'article' => $article
            
            ] );
    }

    #[Route('/showArticle/{id}', name: 'show_article')]
    public function showArticle(?Categorie $categorie): Response
    { 
        if ($categorie) {
            $articles = $categorie->getArticles()->getValues();
        }else {
            
           return $this->redirectToRoute('app_home');
        }
        $categories = $this->repoCategorie->findAll();
         //dd($articles);

         return $this->render("home/index.html.twig",[
            'articles' => $articles,
            'categories' => $categories
            ] );
    }
}
