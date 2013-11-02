<?php
/**
 * Created by PhpStorm.
 * User: neolpar
 * Date: 28/10/13
 * Time: 00:28
 */

namespace Rest\DemoBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rest\DemoBundle\Entity\User;

class LoadUserData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     */
    public function load(ObjectManager $manager)
    {
        $alice = new User();
        $alice->setUsername('alice');
        $alice->setEmail('alice@screensony.com');
        $alice->setPassword('fooalicepassword');

        $bob = new User();
        $bob->setUsername('bob');
        $bob->setEmail('bob@screenfony.com');
        $bob->setPassword('foobobpassword');

        $manager->persist($alice);
        $manager->persist($bob);

        $manager->flush();
    }
}