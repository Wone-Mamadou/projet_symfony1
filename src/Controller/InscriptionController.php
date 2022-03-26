<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;

class InscriptionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager ;
    }

    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);

        // analyse de la requette par le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //traitement des données recues du formulaire
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_home');
            //dd($user);
        }

        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
            'form' =>$form->createView()
        ]);
    }
}
