<?php

namespace Fractal\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fractal\FractalBundle\Entity\Fractal;

class LoadFractalData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $titles = array('Множество Мандельброта', 'Ковёр Серпинского', 'Кривая Коха');
        $files = array('mandelbrot.php', 'serpinskiy.php', 'koh.php');
        $texts = array('Мно́жество Мандельбро́та — это множество таких точек c на комплексной плоскости, для которых итерационная последовательность z_{n+1} = {z_n}^2 + c при z_0 = 0 является сходящейся. То есть, это множество таких c, для которых существует такое действительное R, что неравенство |zn|<R выполняется при всех натуральных n.

Множество Мандельброта является одним из самых известных фракталов, в том числе за пределами математики, благодаря своим цветным визуализациям. Его фрагменты не строго подобны исходному множеству, но при многократном увеличении определённые части всё больше похожи друг на друга.',
                        'Ковёр Серпинского (квадрат Серпинского) — фрактал, один из двумерных аналогов множества Кантора, предложенный польским математиком Вацлавом Серпинским.',
                        'Кривая Коха — фрактальная кривая, описанная в 1904 году шведским математиком Хельге фон Кохом.

Три копии кривой Коха, построенные (остриями наружу) на сторонах правильного треугольника, образуют замкнутую кривую бесконечной длины, называемую снежинкой Коха.');
        $slugs = array('mandelbrot', 'serpinskiy', 'koh');


        foreach($titles as $key => $value){
            $article = new Fractal();
            $article->setTitle($titles[$key]);
            $article->setSlug($slugs[$key]);
            $article->setFile($files[$key]);
            $article->setText($texts[$key]);
            $manager->persist($article);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 9;
    }
} 