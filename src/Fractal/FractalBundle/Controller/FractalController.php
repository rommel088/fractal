<?php

namespace Fractal\FractalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FractalController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT f
            FROM FractalBundle:Fractal f
            ORDER BY f.id ASC'
        );
        $fractals = $query->getResult();

        $viewBlog = $this->container->get('ViewBlog');
        $sidebar = $viewBlog->showSideBar($this->container->getParameter('items_in_sidebar'));

        return $this->render('FractalBundle::main.html.twig', array('sidebar' => $sidebar,
                                                                    'fractals' => $fractals));
    }

    public function showFractalAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT f
            FROM FractalBundle:Fractal f
            WHERE f.slug = :slug
            ORDER BY f.id ASC'
        )->setParameter('slug', $slug);
        $fractal = $query->getSingleResult();

        $viewBlog = $this->container->get('ViewBlog');
        $sidebar = $viewBlog->showSideBar($this->container->getParameter('items_in_sidebar'));

        return $this->render('FractalBundle::fractal.html.twig', array('sidebar' => $sidebar,
                                                                       'fractal' => $fractal));
    }


}