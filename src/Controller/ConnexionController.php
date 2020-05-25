<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Entity\Question;
use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Entity\SuperUtilisateur;
use App\Form\ProfesseurFormType;
use App\Form\ProfSFormType;
use App\Form\SuperUtilisateurFormType;
use App\Repository\QuestionRepository;
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
    */
    public function formulaire(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {

        $utilisateur = new Utilisateur();
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
    * @Route("/inscriptionProf/{id}", name="modif_prof",  methods="GET|POST")
    */
    public function formulaireProf(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder, Professeur $professeur = null)
    {

        if(!$professeur){
            $professeur = new Professeur();
        }
        $form = $this->createForm(ProfSFormType::class,$professeur);
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
    */
    public function formulaireSuper(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $superUtilisateur = new SuperUtilisateur();
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
     * @Route("/editJ/{id}",name="app_editJ",
     * requirements={"id"="\d+"})
     */
    public function editJoueur(Request $request, Utilisateur $utilisateur,EntityManagerInterface $em) {

        $form = $this->createForm(InscriptionType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('chemin');
        }

        return $this->render('connexion/inscription.html.twig', [
                    'form' => $form->createView()]);
    }
    /**
     * @Route("/editP/{id}",name="app_editP",
     * requirements={"id"="\d+"})
     */
    public function editProf(Request $request, Professeur $utilisateur, EntityManagerInterface $em) {

        $form = $this->createForm(ProfesseurFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('chemin');
        }

        return $this->render('connexion/inscriptionProf.html.twig', [
                    'form' => $form->createView()]);
    }
    /**
     * @Route("/editS/{id}",name="app_editS",
     * requirements={"id"="\d+"})
     */
    public function editSuper(Request $request, SuperUtilisateur $utilisateur,EntityManagerInterface $em) {

        $form = $this->createForm(SuperUtilisateurFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('chemin');
        }

        return $this->render('connexion/inscription.html.twig', [
                    'form' => $form->createView()]);
    }
    /**
     * @Route("/consulteBDD", name="consulteBDD")
     */
    public function consulteBDD(UtilisateurRepository $repo, QuestionRepository $repoq)
    {   
        $utilisateurs = $repo->findAll();
        $questions = $repoq->findAll();

        return $this->render('part_fp/consulteBDD.html.twig',[
            'utilisateurs' => $utilisateurs,
            'questions' => $questions
        ]);
    }
        /**
     * @Route("/utilisateur/{id}",name="app_remove_utilisateur", methods={"DELETE"},
     * requirements={"id"="\d+"})
     */
    public function removeJ(Request $request, Utilisateur $utilisateur)
    {

        if ($this->isCsrfTokenValid('remove' . $utilisateur->getId(), $request->request->get('_token'))) {

            $em = $this->getDoctrine()
                ->getManager();

            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('consulteBDD');
    }
    /**
     * @Route("/professeur/{id}",name="app_remove_professeur", methods={"DELETE"},
     * requirements={"id"="\d+"})
     */
    public function removeP(Request $request, Professeur $utilisateur)
    {

        if ($this->isCsrfTokenValid('remove' . $utilisateur->getId(), $request->request->get('_token'))) {

            $em = $this->getDoctrine()
                ->getManager();

            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('consulteBDD');
    }
    /**
     * @Route("/questionS/{id}",name="app_remove_question", methods={"DELETE"},
     * requirements={"id"="\d+"})
     */
    public function removeQ(Request $request, Question $question)
    {

        if ($this->isCsrfTokenValid('remove' . $question->getId(), $request->request->get('_token'))) {

            $em = $this->getDoctrine()
                ->getManager();

            $em->remove($question);
            $em->flush();
        }

        return $this->redirectToRoute('consulteBDD');
    }
}
