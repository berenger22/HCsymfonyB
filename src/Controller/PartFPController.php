<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Question;
use App\Entity\Domaine;
use App\Form\QuestionFormType;
use App\Form\DomainFormType;

class PartFPController extends AbstractController {

    /**
     * @Route("/question", name="app_question")
     */
    public function création(Request $request, UserInterface $user) {
        $em = $this->getDoctrine()->getManager();

        $question = new Question();


        $formQuestion = $this->createForm(QuestionFormType::class, $question);
        $formQuestion->handleRequest($request);
                
        if($formQuestion->isSubmitted() && $formQuestion->isValid())
        {
            $question->setProfesseur($user);
            $em->persist($question);
            $em->flush();
            
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('part_fp/question_reponse.html.twig', [
                    'form' => $formQuestion->createView(),
        ]);
    }
        /**
     * @Route("/question/{id}/edit",name="app_edit",
     * requirements={"id"="\d+"})
     */
    public function edit(Request $request, $id) {

        $em = $this->getDoctrine()
                ->getManager();

        $question = $em->getRepository(Question::Class)
                ->find($id);

        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('part_fp/question_reponse.html.twig', [
                    'form' => $form->createView()]);
    }
    
        /**
     * @Route("/question/{id}",name="app_remove", methods={"DELETE"},
     * requirements={"id"="\d+"})
     */    
        public function remove(Request $request, Question $question) {

        if ($this->isCsrfTokenValid('remove'.$question->getId(),$request->request->get('_token'))) {

            $em = $this->getDoctrine()
                ->getManager();
            
            $em->remove($question);
            $em->flush();

        }

        return $this->redirectToRoute('app_profil');
    }
    /**
     * @Route("/profil", name="app_profil")
     */

    public function profil(Request $request, UserInterface $user) {
        dump($user);
//        $em = $this->getDoctrine()->getManager();    
//        $listeQuestions = $em
//                ->getRepository(Question::Class)
//                ->findBy($idProfesseur);
        $listeQuestions = $user->getQuestions();
        return $this->render('part_fp/profil_prof.html.twig', [
            'listeQuestions' => $listeQuestions
        ]);
    }
//        /**
//     * @Route("/test", name="app_test")
//     */
//    public function test(Request $request) {
//        
//        $em = $this->getDoctrine()->getManager();
//
//        $domaine = new Domaine();
//        $form = $this->createForm(DomainFormType::class, $domaine);
//        $form->handleRequest($request);
//        
//        if($form->isSubmitted() && $form->isValid()){
//
//            $domaine->setDomaine(null);
//            $em->persist($domaine);
//            $em->flush();
//
//            return $this->render('part_fp/index.html.twig', [
//            'form' => $form->createView()
//        ]);
//
//        }
//
//
//        
//        return $this->render('part_fp/index.html.twig', [
//            'form' => $form->createView()
//        ]);
//    }
}
