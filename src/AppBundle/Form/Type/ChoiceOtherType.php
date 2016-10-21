<?php
/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class ChoiceOtherType extends AbstractType implements DataTransformerInterface
{
    const OTHER = '__OTHER__';

    private $currentChoices = [];

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentChoices = $options['choices'];
        $options['choices'][$options['other_label']] = self::OTHER;

        $builder->add('choices', ChoiceType::class, [
            'placeholder' => $options['placeholder'],
            'choices' => $options['choices'],
            'label' => false,
        ]);

        $builder->add('other', TextType::class, [
            'label' => false,
        ]);

        $builder->addModelTransformer($this);

        if ($options['required']) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if (null == $data['choices']) {
                    $form['choices']->addError(new FormError('Por favor seleccione una opcion'));
                } elseif (self::OTHER == $data['choices'] and null == $data['other']) {
                    $form['other']->addError(new FormError('Por favor Indique un valor'));
                }
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => null,
            'other_label' => 'Especificar Otro',
        ]);
        $resolver->setRequired('choices');

        $resolver->setAllowedTypes('choices', 'array');
        $resolver->setAllowedTypes('other_label', 'string');
    }

    public function transform($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (in_array($value, $this->currentChoices)) {
            return [
                'choices' => $value,
            ];
        } else {
            return [
                'choices' => self::OTHER,
                'other' => $value,
            ];
        }
    }

    public function reverseTransform($value)
    {
        if (empty($value)) {
            return;
        }

        return self::OTHER == $value['choices'] ? $value['other'] : $value['choices'];
    }
}
