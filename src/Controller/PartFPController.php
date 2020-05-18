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

class PartFPController extends AbstractController
{

    /**
     * @Route("/question", name="app_question")
     */
    public function création(Request $request, UserInterface $user)
    {
        $em = $this->getDoctrine()->getManager();

        $question = new Question();


        $formQuestion = $this->createForm(QuestionFormType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $i = 0;
            $reponses = $question->getReponses();
            foreach ($reponses as $reponse) {
                if ($reponse->getCorrect()) {
                    $i = $i + 1;
                }
            }
            if ($i == 1) {
                $question->setProfesseur($user);
                $em->persist($question);
                $em->flush();
                $this->addFlash('success', "Votre question a été ajoutée!");
                return $this->render('part_fp/profil_prof.html.twig');
            } else {
                $this->addFlash('error', "Il faut une seule bonne réponse pour que la question soit validée!!");
            }
        }

        return $this->render('part_fp/question_reponse.html.twig', [
            'form' => $formQuestion->createView(),
        ]);
    }
    /**
     * @Route("/question/{id}/edit",name="app_edit",
     * requirements={"id"="\d+"})
     */
    public function edit(Request $request, $id)
    {

        $em = $this->getDoctrine()
            ->getManager();

        $question = $em->getRepository(Question::class)
            ->find($id);

        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $i = 0;
            $reponses = $question->getReponses();
            foreach ($reponses as $reponse) {
                if ($reponse->getCorrect()) {
                    $i = $i + 1;
                }
            }

            if ($i == 1) {
                $em->persist($question);
                $em->flush();
                $this->addFlash('success', "Votre question a été ajoutée!");

                return $this->redirectToRoute('app_profil');
            } else {
                $this->addFlash('error', "Il faut une seule bonne réponse pour que la question soit validée!!");
            }
        }

        return $this->render('part_fp/question_reponse.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/question/{id}",name="app_remove", methods={"DELETE"},
     * requirements={"id"="\d+"})
     */
    public function remove(Request $request, Question $question)
    {

        if ($this->isCsrfTokenValid('remove' . $question->getId(), $request->request->get('_token'))) {

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

    public function profil(Request $request, UserInterface $user)
    {
        // dump($user);
        //        $em = $this->getDoctrine()->getManager();    
        //        $listeQuestions = $em
        //                ->getRepository(Question::Class)
        //                ->findBy($idProfesseur);
        $listeQuestions = $user->getQuestions();
        return $this->render('part_fp/profil_prof.html.twig', [
            'listeQuestions' => $listeQuestions
        ]);
    }

    /**
     * @Route("/domaine", name="app_domaine")
     */
    public function test(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $domaine = new Domaine();
        $form = $this->createForm(DomainFormType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($domaine);
            $em->flush();

            return $this->render('part_fp/domaine.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('part_fp/domaine.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail(Request $request, Question $question)
    {

        return $this->render('part_fp/detail.html.twig', [
            'question' => $question
        ]);
    }
}
