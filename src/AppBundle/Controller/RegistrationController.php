<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        $form = $this->createForm(RegistrationType::class);
        
        return $this->render('registration/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
