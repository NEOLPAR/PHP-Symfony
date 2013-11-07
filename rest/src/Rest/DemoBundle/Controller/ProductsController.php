<?php
/**
 * Created by PhpStorm.
 * User: neolpar
 * Date: 03/11/13
 * Time: 20:55
 */

namespace Rest\DemoBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Rest\DemoBundle\Entity\Product;
use Rest\DemoBundle\Form\ProductType;

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

    /**
     * @param Product $product
     * @return Product
     * @View
     * @ParamConverter("product", class="RestDemoBundle:Product")
     *
     */
    public function putProductAction( Product $product )
    {
        return $this->processForm( $product );
    }

    /**
     * @param Product $product
     * @return Response
     * @View
     * @ParamConverter("product", class="RestDemoBundle:Product")
     *
     */
    public function deleteProductAction( Product $product )
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove( $product );
        $em->flush();

        return new Response( 'Deleted', Codes::HTTP_OK );
    }

    /**
     * @param Product $product
     * @return array
     * @View
     * @ParamConverter("product", class="RestDemoBundle:Product")
     *
     */
    private function processForm ( Product $product )
    {
        $exist = false;

        if( !$product->getId() ) {
            $statusCode = Codes::HTTP_CREATED;
        } else {
            $statusCode = Codes::HTTP_OK;
        }

        $form = $this->createForm( new ProductType(), $product );
        $form->bind( $this->getRequest() );

        if ( $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();

            if( $statusCode === Codes::HTTP_CREATED ) {
                $exist = $em->getRepository('RestDemoBundle:Product')->
                    findOneBy( array(
                            'name' => $product->getName(),
                            'price' => $product->getPrice(),
                            'description' => $product->getDescription()
                            )
                    );
            }

            if ( !$exist ) {
                $em->persist( $product );
                $em->flush();
            } else {
                $product = $exist;
                $statusCode = Codes::HTTP_OK;
            }

            $context = new SerializationContext();
            $context->setSerializeNull( true );

            $serializer = $this->get( 'jms_serializer' );

            $response = new Response( $serializer->serialize( $product, 'json', $context ) );
            $response->headers->set( 'Content-Type', 'application/json' );

            return $response;
        }

        return new Response( $form, Codes::HTTP_BAD_REQUEST );
    }

} 