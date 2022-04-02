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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class InscriptionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager ;
    }

    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        // $plaintextPassword;

        $form = $this->createForm(InscriptionType::class, $user);

        // analyse de la requette par le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //traitement des données recues du formulaire
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
                // $plaintextPassword
            );

            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('connexion');
            //dd($user);
        }

        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
            'form' =>$form->createView()
        ]);
    }

    // #[Route('/login', name: 'connexion')]
    // public function login(): Response{

    //     return $this->render('connexion/index.html.twig', [
    //         'controller_name' => 'InscriptionController',
    //     ]);
    // }
    // #[Route('/login1', name: 'connexion1')]
    // public function index(AuthenticationUtils $authenticationUtils): Response
    //   {
    //         // get the login error if there is one
    //         // dd(100);
    //         $error = $authenticationUtils->getLastAuthenticationError();

    //         // last username entered by the user
    //         $lastUsername = $authenticationUtils->getLastUsername();

    //                 return $this->render('connexion/index.html.twig', [
    //          'controller_name' => 'InscriptionController',
    //          'last_username' => $lastUsername,
    //          'error' => $error,
    //                 ]);
    //   }
}
