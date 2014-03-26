<?php

namespace Fractal\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function IndexAction(Request $request)
    {
        $page = $request->get('page');
        if( !$page ) {
            $page = 1;
        }
//        $searchType = $request->get('type');
//        $searchQuery = $request->get('query');
//        $searchParams = $this->getSearchWhere($searchType, $searchQuery);

        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery('
                    SELECT COUNT(content.id)
                    FROM BlogBundle:Articles content
                    ORDER BY content.created DESC');
//        $query->setParameters($searchParams['params']);
        $count = $query->getSingleScalarResult();
        $hasMore = false;
        if ($count > ($this->container->getParameter('articles_per_page') * $page)) $hasMore = true;

        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery('
                SELECT content
                FROM BlogBundle:Articles content
                ORDER BY content.created DESC');
//        $query->setParameters($searchParams['params']);
        $adapter = new DoctrineORMAdapter($query);

        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage($this->container->getParameter('articles_per_page'));
        $pagerfanta->setCurrentPage($page);
        $articles = $pagerfanta->getCurrentPageResults();

        $result = "";
//        if ($searchQuery) {
//            $search = $searchQuery;
//            $pattern = "/".$search."/is";
//            $replace = '<b style="color:#FF0000; background:#FFFF00;">'.$search.'</b>';
//        }

        foreach($articles as $key=>$value){

            $result[$key]['id'] = $value->getId();
            $result[$key]['title'] = $value->getTitle();
            $result[$key]['slug'] = $value->getSlug();
            $result[$key]['image'] = $value->getImage();
//            if ($searchQuery) {
//                $result[$key]['body'] = preg_replace($pattern, $replace, $value->getBody());
//            } else {
                $result[$key]['body'] = $value->getBody();
//            }
            $result[$key]['viewed'] = $value->getViewed();
//            $result[$key]['tags'] = $this->getArticleTags($value->getId());
            $result[$key]['created'] = $value->getCreated()->format('Y-m-d H:i:s');
            $result[$key]['updated'] = $value->getCreated()->format('Y-m-d H:i:s');
        }

        return $this->render('BlogBundle::home.html.twig', array('articles' => $result,
//                                                                'sidebar' => $this->sidebarDataAction()->getContent(),
                                                                'pagerfanta' => $pagerfanta,
                                                                'hasMore' => $hasMore));
    }
    public function ShowPostAction($slug)
    {
        return $this->render('BlogBundle:Default:index.html.twig', array('name' => $slug));
    }
    public function MorePostsAction()
    {
        return $this->render('BlogBundle:Default:index.html.twig', array('name' => 'namsvsdbvsdbve'));
    }

}
