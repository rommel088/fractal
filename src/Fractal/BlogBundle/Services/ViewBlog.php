<?php

namespace Fractal\BlogBundle\Services;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Fractal\BlogBundle\Entity\TagCloud;

class ViewBlog
{
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function showAllPosts($request, $articlesPerPage)
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

    public function showPost($slug)
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

    public function showSideBar($itemsCount)
    {
        $em = $this->doctrine->getEntityManager();
        $query = $em->createQuery(
            'SELECT p
            FROM BlogBundle:Articles p
            ORDER BY p.created DESC'
        )->setMaxResults($itemsCount);
        $articles = $query->getResult();
        $byCreation = "";
        foreach($articles as $key=>$value){
            $byCreation[$key]['id'] = $value->getId();
            $byCreation[$key]['title'] = $value->getTitle();
            $byCreation[$key]['slug'] = $value->getSlug();
        }

        $query = $em->createQuery(
            'SELECT p
            FROM BlogBundle:Articles p
            ORDER BY p.viewed DESC'
        )->setMaxResults($itemsCount);
        $articles = $query->getResult();
        $byViewed = "";
        foreach($articles as $key=>$value){
            $byViewed[$key]['id'] = $value->getId();
            $byViewed[$key]['title'] = $value->getTitle();
            $byViewed[$key]['slug'] = $value->getSlug();
        }

        $query = $em->createQuery(
            'SELECT p
            FROM GuestBookBundle:Post p
            ORDER BY p.created DESC'
        )->setMaxResults($itemsCount);
        $articles = $query->getResult();
        $posts = "";
        foreach($articles as $key=>$value){
            $posts[$key]['id'] = $value->getId();
            $posts[$key]['title'] = $value->getMessage();
        }

        $query = $em->createQuery(
            'SELECT t.id,
                    t.tag,
                    COUNT(a.id) cnt
            FROM BlogBundle:Articles a
            JOIN a.tags t
            GROUP BY t.id
            ORDER BY cnt DESC')->setMaxResults(15);
        $tags = $query->getResult();
        $cloud = new TagCloud();
        foreach($tags as $key=>$value){
            $cloud->addTag(array('tag' => $value['tag'], 'url' => '/?type=tag&query='.$value['tag'], 'size' => $value['cnt']));
        }
        $cloud->setHtmlizeTagFunction( function($tag, $size) {
            $link = '<a href="'.$tag['url'].'">'.$tag['tag'].'</a>';
            return "<span class='tag size{$size}'>{$link}</span> ";
        });

        $sidebar['bycreation'] = $byCreation;
        $sidebar['byviewed'] = $byViewed;
        $sidebar['posts'] = $posts;
        $sidebar['cloud'] = $cloud->render();
        return $sidebar;
    }
} 