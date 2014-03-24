<?php

namespace Fractal\GuestBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GuestBookBundle:Default:index.html.twig', array('name' => $name));
    }
}
