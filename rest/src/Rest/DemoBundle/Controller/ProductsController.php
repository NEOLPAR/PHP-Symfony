<?php
/**
 * Created by PhpStorm.
 * User: neolpar
 * Date: 03/11/13
 * Time: 20:55
 */

namespace Rest\DemoBundle\Controller;


use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Rest\DemoBundle\Entity\Product;
use Rest\DemoBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends FOSRestController {

    /**
     * @return array
     * @View()
     */
    public function getProductsAction()
    {
        $products = $this->getDoctrine()->getRepository('RestDemoBundle:Product')
            ->findAll();

        return array('product' => $products);
    }

    /**
     * @param Product $product
     * @return array
     * @View
     * @ParamConverter("product", class="RestDemoBundle:Product")
     *
     */
    public function getProductAction(Product $product)
    {
        return array('product' => $product);
    }

    public function postProductAction()
    {
        return $this->processForm( new Product() );
    }

    private function processForm ( Product $product )
    {
        $statusCode = 400;

        $form = $this->createForm( new ProductType(), $product );
        $form->bind( $this->getRequest() );

        if ( $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();

            $exist = $em->getRepository('RestDemoBundle:Product')->findOneBy(array('name'=>$product->getName()));
            if(!$exist) {
                $statusCode = 201;
                $em->persist( $product );
                $em->flush();
            } else {
                $statusCode = 200;
            }

            $response = new Response();
            $response->setStatusCode( $statusCode );
            $data = "";

            if( 201 === $statusCode ) {
                $serializer = $this->container->get( 'serializer' );
                $data = json_encode( array( 'data' => $serializer->serialize( $product, 'json' ) ) );
            }

            return $response->setContent( $response.$data );
        }

        return View::create( $form, $statusCode );
    }

} 