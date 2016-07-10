<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;

class DefaultController extends FOSRestController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
      return $this->forward('AppBundle:ScoresGet:get', array(
        'n' => 10, 'name' => '', 'difficulty' => '', 'sort_field' => 'score' ));
    }

    /**
     * @Route("/login", name="login")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function loginAction(Request $request) {
      $data = array( 'user' => $this->getUser()->getUsername() );
      $view = $this->view($data, 200)
                ->setTemplate('login.html.twig');
      return $this->handleView($view);
    }
}
