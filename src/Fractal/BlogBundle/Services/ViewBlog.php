<?php

namespace Fractal\BlogBundle\Services;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class ViewBlog
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function ShowAllPosts($request, $articlesPerPage)
    {
        $page = $request->get('page');
        if( !$page ) {
            $page = 1;
        }

        $query = $this->doctrine->getEntityManager()
            ->createQuery('
                    SELECT COUNT(content.id)
                    FROM BlogBundle:Articles content
                    ORDER BY content.created DESC');
        $count = $query->getSingleScalarResult();
        $hasMore = false;
        if ($count > ($articlesPerPage * $page)) $hasMore = true;

        $query = $this->doctrine->getEntityManager()
            ->createQuery('
                SELECT content
                FROM BlogBundle:Articles content
                ORDER BY content.created DESC');
        $adapter = new DoctrineORMAdapter($query);

        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage($articlesPerPage);
        $pagerfanta->setCurrentPage($page);
        $articles = $pagerfanta->getCurrentPageResults();

        $posts = "";

        foreach($articles as $key=>$value){

            $posts[$key]['id'] = $value->getId();
            $posts[$key]['title'] = $value->getTitle();
            $posts[$key]['slug'] = $value->getSlug();
            $posts[$key]['image'] = $value->getImage();
            $posts[$key]['body'] = $value->getBody();
            $posts[$key]['viewed'] = $value->getViewed();
//            $posts[$key]['tags'] = $this->getArticleTags($value->getId());
            $posts[$key]['created'] = $value->getCreated()->format('Y-m-d H:i:s');
            $posts[$key]['updated'] = $value->getCreated()->format('Y-m-d H:i:s');
        }
        $result['posts'] = $posts;
        $result['pagerfanta'] = $pagerfanta;
        $result['more'] = $hasMore;

        return $result;
    }

    public function ShowPost($slug)
    {
        $em = $this->doctrine->getEntityManager();
        $query = $em->createQuery(
            "SELECT p
            FROM BlogBundle:Articles p
            WHERE p.slug = '$slug'
            ORDER BY p.created ASC"
        )->setMaxResults(1);
        $articles = $query->getSingleResult();

        $result = "";
        $result['id'] = $articles->getId();
        $result['title'] = $articles->getTitle();
        $result['image'] = $articles->getImage();
        $result['body'] = $articles->getBody();
        $result['viewed'] = $articles->getViewed();
        $result['created'] = $articles->getCreated()->format('Y-m-d H:i:s');
        $result['updated'] = $articles->getCreated()->format('Y-m-d H:i:s');

        return $result;
    }
} 