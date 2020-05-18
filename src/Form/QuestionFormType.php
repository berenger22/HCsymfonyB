<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Domaine;
use App\Form\ReponseFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class QuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domaine', EntityType::class, [
                'class' => Domaine::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => '{{domaine.logo}}'
                    ]
            ])
            ->add('intitule', TextareaType::class)
            ->add('description', TextareaType::class,[
                'required' => false
            ])
            ->add('justification', TextareaType::class,[
                'required' => false
            ])
            ->add('reponses', CollectionType::class, [
                'entry_type' => ReponseFormType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
