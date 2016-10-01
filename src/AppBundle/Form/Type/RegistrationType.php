<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('pais', ChoiceType::class, [
                'placeholder' => 'Seleccionar',
                'choices' => [
                    'Colombia' => 'co',
                    'Venezuela' => 've',
                ],
            ])
            ->add('estado', ChoiceType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
