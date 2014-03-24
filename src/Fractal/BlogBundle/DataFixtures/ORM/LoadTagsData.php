<?php

namespace Fractal\BlogBundle\DataFixtures\ORM;

use Fractal\BlogBundle\Entity\Tags;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTagsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tagsArray = array("new", "funny", "study", "work", "video");
        foreach($tagsArray as $key=>$value){
            $tag = new Tags();
            $tag->setTag($value);
            $manager->persist($tag);
            $this->addReference('tag'.$key, $tag);
        }

        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5;
    }
} 