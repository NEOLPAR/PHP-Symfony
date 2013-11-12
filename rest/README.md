Symfony REST service
====================
This directory contain a sample of REST service, GET, POST, PUT, DELETE and OPTIONS method.

1) Installing and configurating Symfony2 for REST
-------------------------------------------------
We'll install the symfony2 frmework with composer:

``` bash
composer create-project symfony/framework-standard-edition <FOLDER> 2.3.6
cd <FOLDER>
```

Installing dependencies for REST service, FOS REST and JMS Serializer:

``` bash
composer require jms/serializer-bundle @stable
composer require friendsofsymfony/rest-bundle @stable
```
app/AppKernel.php

``` php
$bundles = array(
.... ,
new FOS\RestBundle\FOSRestBundle(),
new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
);
```

2) Configuring listeners
------------------------
app/config/config.yml

``` yml
sensio_framework_extra:
    view:   { annotations: false }
    router: { annotations: true  }

fos_rest:
    routing_loader:
        default_format: json
    view:
        view_response_listener: true
```

3) Generating a New Bundle Skeleton and a new Entity
----------------------------------------------------
First need a new Bundle, we can generate with this wizard

```bash
php app/console generate:bundle
```

Next step is to create a entity. We work over this entity.

``` bash
app/console doctrine:generate:entity
```

4) UsersController and Route
----------------------------
Routing to REST method:

app/config/routing.yml

``` yml
users:
    type:     rest
    resource: Rest\DemoBundle\Controller\UsersController
```
REST URL for this route: http://localhost/rest/web/app_dev.php/users

Create 2 methods getAll and get:

Rest\DemoBundle\Controller\UsersController.php

``` php
<?php
/**
 * Created by PhpStorm.
 * User: neolpar
 * Date: 02/11/13
 * Time: 18:39
 */

namespace Rest\DemoBundle\Controller;


use Rest\DemoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UsersController extends Controller {

    /**
     * @return array
     * @View()
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('RestDemoBundle:User')
            ->findAll();

        return array('users' => $users);
    }

    /**
     * @param User $user
     * @return array
     * @View
     * @ParamConverter("user", class="RestDemoBundle:User")
     *
     */
    public function getUserAction(User $user)
    {
        return array('user' => $user);
    }
}
```

We go to bash and try the route:

``` bash
app/console route:debug
```

5) Creating database and load data to try our GET methods
---------------------------------------------------------
We install doctrine fixtures bundle and include on AppKernel.php

``` bash
composer require doctrine/doctrine-fixtures-bundle dev-master
```
app/AppKernel.php

```php
        $bundles = array(
            ..................... ,
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        );
```

Create 'Rest\DemoBundle\DataFixtures\ORM\LoadUserData.php'

```php
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
```

app/config/parameters.yml

```yml
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: null
    database_name: rest
    database_user: root
    database_password: root
    ...
```

Create database with doctrine and load data with fixtures

```bash
app/console doctrine:database:create
app/console doctrine:schema:create
app/console doctrine:fixtures:load
```

6) Trying with HTTPIE
---------------------
If you don't have installed, you can search it from https://github.com/jkbr/httpie

```bash
http http:\\localhost\rest\web\app_dev.php\users Accept:application/json
```

And the result:

```bash
HTTP/1.1 200 OK
Cache-Control: no-cache
Content-Type: application/json
Date: Sat, 02 Nov 2013 21:00:07 GMT
Server: Apache/2.2.24 (Unix) DAV/2 PHP/5.5.5 mod_ssl/2.2.24 OpenSSL/0.9.8y
Transfer-Encoding: chunked
X-Debug-Token: 009437
X-Powered-By: PHP/5.5.5

{
    "users": [
        {
            "email": "alice@screensony.com", 
            "id": 1, 
            "password": "fooalicepassword", 
            "username": "alice"
        }, 
        {
            "email": "bob@screenfony.com", 
            "id": 2, 
            "password": "foobobpassword", 
            "username": "bob"
        }
    ]
}
```

7) Clearing cache
-----------------
Symfony2 work with cache. You could have any problem with permissions because the web server will work with an user and bash with other.

0) Try the command 'app/console cache:clear' if you have any problem you can solve with the next steps.

1) Clear your cache and logs directories:

```bash
rm -fr app/cache/*
rm -fr app/logs/*
```

2) Search the web server user on /etc/apache2/httpd.conf with the 'User' variable.

3) Change permissions of the directories with those commands:

```bash
sudo chmod +a "www-data allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
```
You should change 'www-data' with the user name from point 2.

8) Removing password from our GET request
-----------------------------------------
We can hide the password on request from our methods with serializer bundle.
All we have to do is create a file on our project directory:

Rest\DemoBundle\Resources\config\serializer\Entity.User.yml

```yml
Rest\DemoBundle\Entity\User:
    exclusion_policy: ALL
    properties:
        id:
            expose: true
        username:
            expose: true
        email:
            expose: true
```

We'll exclude all the fields and the wanted fields put expose to true

9) Create a Product 
-------------------
I repeat previus steps to create other rest entity called Product wich have 'id, name, price and description' fields. We keep making the next things with this entity.

First you have to generate a form automatically using the command line tool:

```bash
app/console generate:doctrine:form RestDemoBundle:Product
```

This give us a form type of product, we change the CSRF protection to false, because doesn't make much sense for a REST API.
Rest / DemoBundle / Form / ProductType.php

```php
<?php

namespace Rest\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rest\DemoBundle\Entity\Product',
            //CSRF_PROTECTION in REST context doesn't make sense.
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'product';
    }
}
```

10) Before POST method
----------------------
Create a processForm method to help us in the future.

Rest / DemoBundle / Controller / ProductsController.php

```php
    /**
     * @param Product $product
     * @return response
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

            $response = new Response( $serializer->serialize( $product, 'json', $context ), $statusCode );
            $response->headers->set( 'Content-Type', 'application/json' );

            return $response;
        }

        return new Response( $form, Codes::HTTP_BAD_REQUEST );
    }

} 
```

11) Validation for Product POST
-------------------------------
If we want validate data, we can create a file like this for example.

Rest / DemoBundle / Resources / config / validation.yml

```yml
Rest\DemoBundle\Model\Product:
    getters:
        name:
            - NotBlank:
        price:
            - NotBlank:
```

12) POST, PUT and DELETE Methods
--------------------------------
We are now ready to create this methods, this is easy thanks to create a formProcess function.

1) POST
```php
    public function postProductAction()
    {
        return $this->processForm( new Product() );
    }
```

2) PUT
```php
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
```

3) DELETE
```php
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
```

13) OPTIONS Methods
-------------------
If we have problems because OPTIONS method response 405 Method not allowed, we can create this method and return a void response to solve this.

```php
    public function optionsProductAction( Product $product )
    {
        return new Response('Ok', codes::HTTP_NO_CONTENT);
    }
```
