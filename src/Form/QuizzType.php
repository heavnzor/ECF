<?php

namespace App\Form;

use App\Entity\Quizz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class QuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('section')
            ->add('question1')
            ->add('reponse1')
            ->add('reponse2')
            ->add('bonnereponse1', ChoiceType::class, [
                'choices'  => [
                    'Réponse 1' => 'reponse1',
                    'Réponse 2' => 'reponse2'
                ],
            ])
            ->add('question2')
            ->add('reponse3')
            ->add('reponse4')
            ->add('bonnereponse2', ChoiceType::class, [
                'choices'  => [
                    'Réponse 1' => 'reponse3',
                    'Réponse 2' => 'reponse4',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quizz::class,
        ]);
    }
}
