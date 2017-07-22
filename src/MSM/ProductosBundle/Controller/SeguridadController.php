<?php

namespace MSM\ProductosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SeguridadController extends Controller
{

    public function loginAction(Request $request)
    {   
        $authen = $this->get('security.authentication_utils');
        $error = $authen->getLastAuthenticationError();

        $last = $authen->getLastUsername();

        return $this->render('ProductosBundle:security:login.html.twig', array('last' => $last, 'error' => $error));
    }

}