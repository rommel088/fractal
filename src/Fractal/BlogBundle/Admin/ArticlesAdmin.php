<?php

namespace Fractal\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

use Doctrine\ORM\EntityRepository;

class ArticlesAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text')
            ->add('image', 'text')
            ->add('tags', 'entity', array(
                'multiple' => true,
                'class' => 'Fractal\BlogBundle\Entity\Tags',
                'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('tags')
                            ->orderBy('tags.tag', 'ASC');
                    },
                'property' => 'tag',
                'label' => 'tags'
            ))
            ->add('body')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('slug')
        ;
    }
} 