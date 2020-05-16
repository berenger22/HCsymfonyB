<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\Professeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Question::class);
    }

    public function getProfesseurQuestions(Professeur $professeur, $questions) {
        $qb = $this->createQueryBuilder('q');
        return $qb->where($qb->expr()->notIn('q.id', ':questions'))
                        ->join('q.professeur', 'p')
                        ->andWhere('p.id = :professeur_id')
                        ->join('q.reponses', 'r')
                        ->groupBy('q')
                        ->having($qb->expr()->gte('COUNT(r)', 2))
                        ->join('q.reponses', 'r2')
                        ->andWhere('r2.correct = true')
                        ->orderBy('q.id', 'ASC')
                        ->setParameters(['professeur_id' => $professeur->getId(), 'questions' => $questions])
                        ->getQuery()
                        ->getResult();
    }

    // /**
    //  * @return Question[] Returns an array of Question objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('q')
      ->andWhere('q.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('q.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Question
      {
      return $this->createQueryBuilder('q')
      ->andWhere('q.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
