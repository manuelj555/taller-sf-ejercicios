<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $paises = $options['paises'];

        $builder
            ->add('nombre', null, [
                'constraints' => new NotBlank(),
            ])
            ->add('pais', ChoiceType::class, [
                'constraints' => new NotBlank(),
                'placeholder' => 'Seleccionar',
                'choices' => $paises,
                'choice_value' => 'code',
                'choice_label' => 'label',
            ])
            ->add('estado', ChoiceType::class)
            ->add('profesion', ChoiceOtherType::class, [
                'required' => true,
                'placeholder' => 'Seleccionar',
                'choices' => [
                    'Recursos Humanos' => 'rrhh',
                    'Sistemas' => 'sis',
                    'Contabilidad' => 'cont',
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($paises) {
            $form = $event->getForm();
            $data = $event->getData();

            $pais = $data['pais'] ?? null;

            $form->add('estado', ChoiceType::class, [
                'choices' => isset($paises[$pais]) ? $paises[$pais]->estados : [],
                'constraints' => new NotBlank(),
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $paises = [
            (object) [
                'code' => 'co',
                'label' => 'Colombia',
                'estados' => [
                    'Bogota' => 1,
                    'Cali' => 2,
                ],
            ],
            (object) [
                'code' => 've',
                'label' => 'Venezuela',
                'estados' => [
                    'Coro' => 1,
                    'Zulia' => 2,
                ],
            ],
        ];

        $resolver->setDefined('paises');
        $resolver->setAllowedTypes('paises', ['array', '\Traversable']);

        $resolver->setNormalizer('paises', function ($options, $items) use ($paises) {
            $values = [];

            foreach (array_merge($paises, $items) as $pais) {
                $values[$pais->code] = $pais;
            }

            return $values;
        });
    }
}
