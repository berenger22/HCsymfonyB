<?php

namespace App\Form;

use App\Entity\Domaine;
use App\Entity\Question;
use App\Entity\Professeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule')
            ->add('description')
            ->add('justification')
            ->add('visible')
            ->add('professeur', EntityType::class, [
                'class' => Professeur::class,
                'choice_label' => 'firstname'])
            ->add('domaine', EntityType::class, [
                'class' => Domaine::class,
                'choice_label' => 'name'])
            ->add('reponses', CollectionType::class, [
                    'entry_type' => ReponseType::class,
                    'entry_options' => ['label' => false],
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true])    
                    
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
