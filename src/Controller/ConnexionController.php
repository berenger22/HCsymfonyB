<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/", name="connexion")
     */
    public function index(AuthenticationUtils $util)
    {
        return $this->render('connexion/index.html.twig',[
            'util' => $util->getLastUsername(),
            'error' => $util->getLastAuthenticationError()
        ]);
    }

    /**
    * @Route("/inscription", name="inscription")
    * @Route("/modif/{id}", name="modif")
    */
    public function formulaire(Utilisateur $utilisateur = null, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        if(!$utilisateur){
             $utilisateur = new Utilisateur();
        }
        $form = $this->createForm(InscriptionType::class,$utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $passwordCrypte = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($passwordCrypte);
            $utilisateur->setRoles("ROLE_USER");
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', "Vous etes bien inscrit");
            return $this->redirectToRoute('connexion');

        }
        return $this->render('connexion/inscription.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
     
    }

    
    /**
     * @Route("/chemin", name="chemin")
     */
    public function chemin(AuthenticationUtils $util)
    {
     
        return $this->render('connexion/chemin.html.twig',[
            'util' => $util->getLastUsername(),
            'error' => $util->getLastAuthenticationError(),
        ]);
    }
}
