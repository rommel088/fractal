<?php

namespace Fractal\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BlogController extends Controller
{
    public function IndexAction(Request $request)
    {
        $viewBlog = $this->container->get('ViewBlog');
        $posts = $viewBlog->ShowAllPosts($request, $this->container->getParameter('articles_per_page'));
        return $this->render('BlogBundle::home.html.twig', array('articles' => $posts['posts'],
//                                                                'sidebar' => $this->sidebarDataAction()->getContent(),
                                                                'pagerfanta' => $posts['pagerfanta'],
                                                                'hasMore' => $posts['more']));
    }
    public function ShowPostAction($slug)
    {
        $viewBlog = $this->container->get('ViewBlog');
        $post = $viewBlog->showPost($slug);
        return $this->render('BlogBundle::article.html.twig', array('article' => $post));
    }

    public function MorePostsAction()
    {
        return $this->render('BlogBundle:Default:index.html.twig', array('name' => 'namsvsdbvsdbve'));
    }

}
