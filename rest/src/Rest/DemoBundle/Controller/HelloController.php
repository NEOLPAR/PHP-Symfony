<?php
/**
 * Created by PhpStorm.
 * User: neolpar
 * Date: 02/11/13
 * Time: 22:52
 */

namespace Rest\DemoBundle\Controller;


use Symfony\Component\HttpFoundation\Response;

class HelloController {

    public function indexAction( $name )
    {
        return new Response('<html><body>Hello '.$name.'!</body></html>');
    }

} 