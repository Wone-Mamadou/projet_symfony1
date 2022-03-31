<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $repoArticle;
    private $repoCategorie;
    private $entityManager;
    private $repoComment;

    public function __construct(ArticleRepository $repoArticle,CategorieRepository $repoCategorie, 
                                EntityManagerInterface $entityManager, CommentaireRepository $repoComment                   
                               )
    {
        $this->repoArticle = $repoArticle;
        $this->repoCategorie = $repoCategorie;
        $this->entityManager = $entityManager ;
        $this->repoComment = $repoComment;
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
    public function show(Article  $article, Request $request, $id): Response
    { 

        //$article = $this->repoArticle->find($id);
        if (!$article) {
            return $this->redirectToRoute('home');
        }

        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);

        // analyse de la requette par le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //traitement des données recues du formulaire
            $commentaire->setArticle($article);
        //    dd($commentaire);
            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();
            return $this->redirectToRoute('show',["id" =>$id]);
            
        }

       // dd($this->repoComment->findByArticle($article));
        
        return $this->render("show/index.html.twig",[
            'form' =>$form->createView(),
            'article' => $article,
            'commentaires' => $this->repoComment->findByArticle($article)
            
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
