<?php
/**
 * Created by PhpStorm.
 * User: neolpar
 * Date: 03/11/13
 * Time: 21:04
 */

namespace Rest\DemoBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rest\DemoBundle\Entity\Product;

class LoadProductData implements FixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     */
    function load(ObjectManager $manager)
    {
        $computer = new Product();
        $computer->setName('Computer');
        $computer->setPrice(11.1);
        $computer->setDescription('A computer');

        $monitor = new Product();
        $monitor->setName('Monitor');
        $monitor->setPrice(112.1);
        $monitor->setDescription('A monitor');

        $manager->persist($computer);
        $manager->persist($monitor);

        $manager->flush();
    }
}