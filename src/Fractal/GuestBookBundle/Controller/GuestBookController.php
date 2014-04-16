<?php

namespace Fractal\GuestBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Fractal\GuestBookBundle\Forms\Guest;
use Fractal\GuestBookBundle\Entity\Post;

class GuestBookController extends Controller
{
    public function indexAction(Request $request)
    {
        $guest = new Guest();
        $form = $this->createFormBuilder($guest)
            ->add('name', 'text')
            ->add('email', 'text')
            ->add('message', 'textarea')
            ->add('save', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {

            $guest = new Post();
            $guest->setName($form->getData()->getName());
            $guest->setEmail($form->getData()->getEmail());
            $guest->setMessage($form->getData()->getMessage());

            $em = $this->getDoctrine()->getManager();
            $em->persist($guest);
            $em->flush();

            return $this->redirect($this->generateUrl('guest_book_index'));

        }

        $page = $request->get('page');
        if( !$page ) {
            $page = 1;
        }

        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery('
                    SELECT COUNT(content.id)
                    FROM GuestBookBundle:Post content
                    ORDER BY content.created DESC');
        $count = $query->getSingleScalarResult();
        $hasMore = false;
        if ($count > ($this->container->getParameter('posts_per_page') * $page)) $hasMore = true;

        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery('
                SELECT content
                FROM GuestBookBundle:Post content
                ORDER BY content.created DESC');
        $adapter = new DoctrineORMAdapter($query);

        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage($this->container->getParameter('posts_per_page'));
        $pagerfanta->setCurrentPage($page);
        $messages = $pagerfanta->getCurrentPageResults();

        $viewBlog = $this->container->get('ViewBlog');
        $sidebar = $viewBlog->showSideBar($this->container->getParameter('items_in_sidebar'));


        return $this->render('GuestBookBundle::guestBook.html.twig', array(
                                                                            'form' => $form->createView(),
                                                                            'messages' => $messages,
                                                                            'sidebar' => $sidebar,
                                                                            'pagerfanta' => $pagerfanta,
                                                                            'hasMore' => $hasMore
                                                                        ));
    }
} 