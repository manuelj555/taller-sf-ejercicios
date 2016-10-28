<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\OptionsResolver\Options;

/**
 * @author Manuel Aguirre
 */
class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="registration")
     */
    public function formAction(Request $request)
    {
        $form = $this->createForm(RegistrationType::class, [
//            'profesion' => 'Lavandería'
        ], [
            'paises' => [
                (object)[
                    'code' =>'mx',
                    'label' => 'Mexico',
                    'estados' => [
                        'Michoacan' => 1,
                        'Sinaloa' => 2,
                    ],
                ],
            ],
        ]);
        $form->handleRequest($request);

        $form->isSubmitted() and dump($form->getData());

    /*    $r = $this->get('validator')->validate('ab', [
            new \Symfony\Component\Validator\Constraints\Length([
                'min' => 3,
                // 'groups' => ['a'],
            ]),
            new \Symfony\Component\Validator\Constraints\Email([
                'groups' => ['b'],
            ])
        // ], ['a', 'b']);
        ], new \Symfony\Component\Validator\Constraints\GroupSequence(['Default', 'b']));

        dump($r);die;*/
        
        return $this->render('registration/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
