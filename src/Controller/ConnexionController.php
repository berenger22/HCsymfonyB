<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Entity\SuperUtilisateur;
use App\Form\ProfesseurFormType;
use App\Form\SuperUtilisateurFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/", name="connexion")
     */
    public function index(AuthenticationUtils $util)
    {
        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('chemin');  
        }
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

     /**
    * @Route("/inscriptionProf", name="inscription_prof")
    * @Route("/modif/{id}", name="modif")
    */
    public function formulaireProf(Professeur $professeur = null, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        if(!$professeur){
             $professeur = new Professeur();
        }
        $form = $this->createForm(ProfesseurFormType::class,$professeur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $passwordCrypte = $encoder->encodePassword($professeur, $professeur->getPassword());
            $professeur->setPassword($passwordCrypte);
            $entityManager->persist($professeur);
            $entityManager->flush();
            $this->addFlash('success', "Vous etes bien inscrit");
            return $this->redirectToRoute('connexion');

        }
        return $this->render('connexion/inscriptionProf.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/test", name="inscription_test")
    * @Route("/modif/{id}", name="modif")
    */
    public function formulaireSuper(Professeur $superUtilisateur = null, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        if(!$superUtilisateur){
             $superUtilisateur = new SuperUtilisateur();
        }
        $form = $this->createForm(SuperUtilisateurFormType::class,$superUtilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $passwordCrypte = $encoder->encodePassword($superUtilisateur, $superUtilisateur->getPassword());
            $superUtilisateur->setPassword($passwordCrypte);
            $entityManager->persist($superUtilisateur);
            $entityManager->flush();
            $this->addFlash('success', "Vous etes bien inscrit");
            return $this->redirectToRoute('connexion');

        }
        return $this->render('connexion/inscription.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/consulteBDD", name="consulteBDD")
     */
    public function consulteBDD(AuthenticationUtils $util, UtilisateurRepository $repo)
    {   
        $utilisateurs = $repo->findAll();
        return $this->render('part_fp/consulteBDD.html.twig',[
            'util' => $util->getLastUsername(),
            'utilisateurs' => $utilisateurs,
            'error' => $util->getLastAuthenticationError(),
        ]);
    }
}
