<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      return $this->forward('AppBundle:ScoresGet:get', array(
        'n' => 10, 'name' => '', 'difficulty' => '', 'sort_field' => 'score' ));
    }
}
