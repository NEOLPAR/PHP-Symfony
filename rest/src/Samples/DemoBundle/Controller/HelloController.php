<?php
/**
 * Created by PhpStorm.
 * User: neolpar
 * Date: 02/11/13
 * Time: 23:20
 */

namespace Samples\DemoBundle\Controller;


use Symfony\Component\HttpFoundation\Response;

class HelloController {

    public function indexAction ( $name )
    {
        return new Response('Hello'.$name.'!');
    }

} 