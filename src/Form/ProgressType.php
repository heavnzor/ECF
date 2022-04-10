<?php

namespace App\Form;

use App\Entity\Progress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('formationFinished')
            ->add('coursFinished')
            ->add('user')
            ->add('formation')
            ->add('cours')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Progress::class,
        ]);
    }
}
