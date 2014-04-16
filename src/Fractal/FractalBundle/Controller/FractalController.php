<?php

namespace Fractal\FractalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FractalController extends Controller
{
    public function indexAction()
    {
        return $this->render('FractalBundle:Default:index.html.twig');
    }
}