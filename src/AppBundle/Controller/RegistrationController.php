<?php

namespace AppBundle\Controller;

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
    }
}
