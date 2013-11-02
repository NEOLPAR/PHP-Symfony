<?php
/**
 * Created by PhpStorm.
 * User: neolpar
 * Date: 02/11/13
 * Time: 23:20
 */

namespace Samples\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller {

    public function indexAction ( $name )
    {
        //draw a template
        return $this->render(
            'SamplesDemoBundle:Hello:index.html.twig',
            array( 'name' => $name )
        );
    }

} 