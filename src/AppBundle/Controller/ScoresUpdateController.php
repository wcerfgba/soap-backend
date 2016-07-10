<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Util;
use AppBundle\Entity\Score;
use Doctrine\ORM\ORMException;

class ScoresUpdateController extends Controller {
  /**
   * @Route("/score", name="newScoreForm")
   * @Method({"GET"})
   */
  public function getAction() {
    return $this->render('postForm.html.twig');
  }

  /**
   * @Route("/score", name="newScore")
   * @Method({"POST"})
   */
  public function postAction(Request $request) {
    $format = $request->getRequestFormat();

    $params = array( 'name', 'difficulty', 'score' );

    foreach ($params as $param) {
      if (!$request->request->has($param)) {
       return new Response(
        $this->renderView("error.$format.twig", array(
          'error' => "Unspecified field: $param"
        )),
        400);
      }
    }
        
    $name = $request->request->get('name'); 
    $difficulty = $request->request->get('difficulty'); 
    $scoreVal = $request->request->get('score');

    $score = new Score();
    try {
      $score->setName($name);
      $score->setDifficulty($difficulty);
      $score->setScore($scoreVal);
    } catch (ORMException $e) {
      return new Response(
       $this->renderView("error.$format.twig", array(
         'error' => "Validation error: {$e->getMessage()}"
       )),
       400);
    }

    $em = $this->getDoctrine()->getManager();
    $em->persist($score);
    $em->flush();

    return $this->render("postSuccess.$format.twig");
  }

  /**
   * @Route("/score/{id}")
   * @Method({"DELETE"})
   */
  public function deleteAction($id) {

  }
} 
