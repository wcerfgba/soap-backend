<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Util;
use AppBundle\Entity\Score;
use AppBundle\Form\ScoreType;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class ScoresUpdateController extends FOSRestController {
  /**
   * @Route("/score", name="newScore")
   */
  public function postAction(Request $request) {
    $score = new Score();
    $form = $this->createForm(ScoreType::class, $score);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($score);
      $em->flush();

      $view = $this->view(null, 200)
                ->setTemplate('postSuccess.html.twig');
      return $this->handleView($view);
    }

    $view = $this->view($form, 400)
              ->setTemplate('postForm.html.twig');
    return $this->handleView($view);
  }

  /**
   * @Route("/delete/{id}", name="deleteScore")
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function deleteAction($id) {
    $em = $this->getDoctrine()->getManager();
    $score = $em->getRepository('AppBundle:Score')->find($id);
    if ($score === null) {
      $view = $this->view(array( 'error' => "Score with $id not found." ), 400)
                ->setTemplate('error.html.twig');
      return $this->handleView($view);
    }
    $em->remove($score);
    $em->flush();

    $view = $this->view(null, 200)
              ->setTemplate('deleteSuccess.html.twig');
    return $this->handleView($view);
  }
} 
