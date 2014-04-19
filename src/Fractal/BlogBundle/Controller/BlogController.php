<?php

namespace Fractal\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BlogController extends Controller
{
    public function indexAction(Request $request)
    {
        $viewBlog = $this->container->get('ViewBlog');
        $posts = $viewBlog->showAllPosts($request, $this->container->getParameter('articles_per_page'));
        $sidebar = $viewBlog->showSideBar($this->container->getParameter('items_in_sidebar'));
        return $this->render('BlogBundle::home.html.twig', array('articles' => $posts['posts'],
                                                                'sidebar' => $sidebar,
                                                                'pagerfanta' => $posts['pagerfanta'],
                                                                'hasMore' => $posts['more']));
    }
    public function showPostAction($slug)
    {
        $viewBlog = $this->container->get('ViewBlog');
        $post = $viewBlog->showPost($slug);
        $sidebar = $viewBlog->showSideBar($this->container->getParameter('items_in_sidebar'));
        return $this->render('BlogBundle::article.html.twig', array('article' => $post,
                                                                    'sidebar' => $sidebar));
    }

    public function morePostsAction()
    {
        return $this->render('BlogBundle:Default:index.html.twig', array('name' => 'namsvsdbvsdbve'));
    }


}
