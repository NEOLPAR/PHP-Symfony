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