Symfony Standard Edition
========================

This directory contain a sample of method GET with REST service.

1) Installing and configurating Symfony2 for REST
-------------------------------------------------

We'll install the symfony2 frmework with composer:

''' bash
composer create-project symfony/framework-standard-edition <FOLDER> 2.3.6
cd <FOLDER>
'''

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

2) Creating User entity with doctrine
-------------------------------------
