<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
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
      $format = $request->getRequestFormat();

      return $this->render("login.$format.twig");
    }
}
