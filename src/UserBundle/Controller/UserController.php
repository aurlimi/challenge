<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    public function indexAction()
    {
        //todo liste des commandes clients
        return $this->render('UserBundle:Default:index.html.twig');
    }


}
