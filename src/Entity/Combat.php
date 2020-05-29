<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class Combat {

    /**
     * @Groups({"attribute"})
     */
    private $lifePoints;

    /**
     * @Groups({"attribute"})
     */
    private $opponentLifePoints;

    /**
     * @Groups({"attribute"})
     */
    private $questionsCorrectes;

    /**
     * @Groups({"attribute"})
     */
    private $questionsIncorrectes;

    function __construct() {
        $this->setLifePoints(100);
        $this->setOpponentLifePoints(100);
        $this->questionsCorrectes = [];
        $this->questionsIncorrectes = [];
    }

    function getLifePoints(): int {
        return $this->lifePoints;
    }

    function getOpponentLifePoints(): int {
        return $this->opponentLifePoints;
    }

    function getQuestionsCorrectes() {
        return $this->questionsCorrectes;
    }
    function setQuestionsCorrectes($questionsCorrectes): void {
        $this->questionsCorrectes = $questionsCorrectes;
    }

    function getQuestionsIncorrectes() {
        return $this->questionsIncorrectes;
    }

    function setQuestionsIncorrectes($questionsIncorrectes): void {
        $this->questionsIncorrectes = $questionsIncorrectes;
    }
    public function addQuestionCorrecte(int $id): void {
        $this->questionsCorrectes[] = $id;
    }

    public function addQuestionIncorrecte(int $id): void {
        $this->questionsIncorrectes[] = $id;
    }

    function setLifePoints($lifePoints): void {
        $this->lifePoints = $lifePoints;
    }

    function setOpponentLifePoints($opponentLifePoints): void {
        $this->opponentLifePoints = $opponentLifePoints;
    }

}
