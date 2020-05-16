<?php

namespace App\DataFixtures;

use App\Entity\Domaine;
use App\Entity\Reponse;
use App\Entity\Question;
use App\Entity\Professeur;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
}
    public function load(ObjectManager $manager) {
        $utilisateurs = [];
        for ($i = 0; $i < 10; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setEmail("test@test.test" . $i);
            $utilisateur->setFirstname("Firstname" . $i);
            $utilisateur->setLastname("Name" . $i);
            $utilisateur->setUsername("Username" . $i);
            $utilisateur->setPassword($this->passwordEncoder->encodePassword($utilisateur,'root'));
            $utilisateurs[] = $utilisateur;
            $manager->persist($utilisateur);
        }
        $manager->flush();

        $professeurs = [];
        for ($i = 10; $i < 20; $i++) {
            $professeur = new Professeur();
            $professeur->setEmail("test@test.test" . $i);
            $professeur->setFirstname("Firstname" . $i);
            $professeur->setLastname("Name" . $i);
            $professeur->setUsername("Username" . $i);
            $professeur->setPassword($this->passwordEncoder->encodePassword($professeur,'root'));
            $professeur->setAvatar("/images/bruno.png");
            $professeurs[] = $professeur;
            $manager->persist($professeur);
        }
        $manager->flush();

        $domaines = [];
        for ($i = 0; $i < 4; $i++) {
            $domaine = new Domaine();
            $domaine->setName('Domaine ' . $i);
            $domaine->setLogo('fa-java');
            $domaines[] = $domaine;
            $manager->persist($domaine);
        }
        $manager->flush();

        $questions = [];
        for ($i = 0; $i < 40; $i++) {
            $question = new Question();
            $question->setDomaine($domaines[(int) $i / 10]);
            $question->setIntitule('Quel est le ' . $i . '?');
            $question->setJustification('Tout à fait Jean-Pierre !');
            $question->setDescription('BlaBla');
            $question->setVisible(true);
            $question->setProfesseur($professeurs[(int) $i / 4]);
            $questions[] = $question;
            $manager->persist($question);
        }
        $manager->flush();

        $reponses = [];
        for ($i = 0; $i < 160; $i++) {
            $reponse = new Reponse();
            $reponse->setQuestion($questions[(int) $i / 4]);
            $reponse->setIntitule('Reponse' . $i);
            $reponse->setCorrect(($i % 4 == 0) ? true : false);
            $reponses[] = $reponse;
            $manager->persist($reponse);
        }
        $manager->flush();
    }

}
