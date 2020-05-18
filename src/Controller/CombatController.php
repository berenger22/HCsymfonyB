<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProfesseurRepository;
use App\Entity\Combat;
use App\Entity\Professeur;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Session\Session;

class CombatController extends AbstractController
{

    /**
     * @Route("/maraudeur", name="maraudeur")
     */
    public function maraudeur(Request $request, ProfesseurRepository $professeurRepository)
    {
        $user = $this->getUser();
        $professeurs = $professeurRepository->findProfesseurWithQuestions($this->getUser());
        $professeur = $professeurs[array_rand($professeurs, 1)];
        return $this->render('combat/maraudeur.html.twig',
        ['professeur' => $professeur,'user' => $user]);
    }

    /**
     * @Route("/combat/{id}", name="combat")
     */
    public function index(Request $request, Professeur $professeur)
    {
        $session = $request->getSession(); //new Session();
        $session->start();

        $combat = new Combat();

        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer);
        $combat_tab = $serializer->normalize($combat, null, [AbstractNormalizer::ATTRIBUTES => ['lifePoints', 'opponentLifePoints', 'questionsCorrectes', 'questionsIncorrectes']]);

        $session->set('combat', $combat_tab);
        return $this->render('combat/index.html.twig', [
            'controller_name' => 'CombatController', 'professeur' => $professeur, 'nb_questions' => min([count($professeur->getQuestions()), 5])
        ]);
    }

    /**
     * @Route("/get/question/{id}", name="ajax_question")
     */
    public function getProfesseurQuestions(Professeur $professeur, Session $session, QuestionRepository $questionRepository): Response
    {
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer);
        $combat_tab = $session->get('combat');
        $questions = $questionRepository->getProfesseurQuestions($professeur, array_merge($combat_tab['questionsCorrectes'], $combat_tab['questionsIncorrectes'], [0]));
        if (!empty($questions)) {
            $question = $questions[array_rand($questions, 1)];
            $question_tab = $serializer->normalize($question, null, [AbstractNormalizer::ATTRIBUTES => ['id', 'intitule', 'description', 'reponses' => ['id', 'intitule'], 'domaine' => ['logo']]]);
            shuffle($question_tab['reponses']);
            return $this->json(['nb_questions' => count($questions), 'question' => $question_tab], 200);
        } else {
            return $this->json($combat_tab, 202);
        };
    }

    /**
     * @Route("/post/question", name="ajax_post_question")
     */
    public function postQuestion(Request $request, Session $session): Response
    {
        $nb_questions = $request->request->get('nb_questions');
        $degats = ceil(100 / floor(($nb_questions + 1) / 2));

        $question = $this->getDoctrine()->getRepository(Question::class)->find($request->request->get('question_id'));
        $correct = '0';
        foreach ($question->getReponses() as $reponse) {
            if ($reponse->getId() == $request->request->get('reponse_id')) {
                ($reponse->getCorrect()) ? $correct = '1' : $correct = '0';
            }
        }

        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer);
        $combat_tab = $session->get('combat');
        $combat = $serializer->denormalize(
            ['lifePoints' => $combat_tab['lifePoints'], 'opponentLifePoints' => $combat_tab['opponentLifePoints'], 'questionsCorrectes' => $combat_tab['questionsCorrectes'], 'questionsIncorrectes' => $combat_tab['questionsIncorrectes']],
            'App\Entity\Combat'
        );
        if ($correct === '1') {
            $combat->setOpponentLifePoints($combat->getOpponentLifePoints() - $degats);
            $combat->addQuestionCorrecte((int) $request->request->get('question_id'));
        } else {
            $combat->setLifePoints($combat->getLifePoints() - $degats);
            $combat->addQuestionIncorrecte((int) $request->request->get('question_id'));
        }
        $combat_tab = $serializer->normalize($combat, null, [AbstractNormalizer::ATTRIBUTES => ['lifePoints', 'opponentLifePoints', 'questionsCorrectes', 'questionsIncorrectes']]);
        $session->set('combat', $combat_tab);
        return $this->json(['correct' => $correct, 'justification' => $question->getJustification(), 'combat' => $combat_tab], 200);
    }
}
