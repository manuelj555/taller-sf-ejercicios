<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    private $profesiones = [
        'Recursos Humanos' => 'rrhh',
        'Sistemas' => 'sis',
        'Contabilidad' => 'cont',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $paises = $options['paises'];

        $builder
            ->add('nombre', null, [
                'constraints' => new NotBlank(),
            ])
            ->add('profesion', ChoiceOtherType::class, [
                'required' => true,
                'placeholder' => 'Seleccionar',
                'choices' => $this->profesiones,
                //'other_label' => 'Otro por favor',
                'other_type' => ChoiceType::class,
            ])
            ->add('pais', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'constraints' => new NotBlank([
                    'groups' => ['profesionConocida'],
                ]),
                'placeholder' => 'Seleccionar',
                'choices' => $paises,
                'choice_value' => 'code',
                'choice_label' => 'label',
            ])
            ->add('estado', ChoiceType::class, [
                'label' => 'Estado, Provincia o Departamento',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($paises) {
            $form = $event->getForm();
            $data = $event->getData();

            $pais = $data['pais'] ?? null;

            $form->add('estado', ChoiceType::class, [
                'choices' => isset($paises[$pais]) ? $paises[$pais]->estados : [],
                'constraints' => new NotBlank([
                    'groups' => ['profesionConocida'],
                ]),
                'label' => 'Estado, Provincia o Departamento',
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

        $resolver->setDefault('validation_groups', function (FormInterface $form) {
            $groups = ['Default'];

            if (in_array($form['profesion']->getData(), $this->profesiones)) {
                $groups[] = 'profesionConocida';
            }

            return $groups;
        });
    }
}
