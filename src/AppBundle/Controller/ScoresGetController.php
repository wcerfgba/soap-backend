<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;

class ScoresGetController extends FOSRestController {
  /**
   * @Route("/filter", name="filterFormHandler")
   * @Method({"GET"})
   *
   * Translates query parameters from a form into an API URL and redirects the 
   * client.
   */
  public function formHandlerAction(Request $request) {
    $fields = array( 'num' => 10, 'name' => '', 'difficulty' => '',
                     'sort_by' => 'score' );
    $path = '';
    foreach ($fields as $field => $default) {
      $val = $request->query->get($field, $default);

      if ($val) {
        $path .= "/$field/$val";
      }
    }

    return $this->redirect($path);
  }

  public function getAction($n = 10, $name = '', $difficulty = '', $sort_field = 'score', Request $request) {
    $repository = $this->getDoctrine()->getRepository('AppBundle:Score');

    // Query the top n results first. This sets up the rank as the keys and 
    // establishes the semantics of the query as operating on the top scores.
    $topN = $repository->createQueryBuilder('s')
              ->orderBy('s.score', 'DESC')
              ->setMaxResults($n)
              ->getQuery()
              ->getArrayResult();

    // Filter results by name and difficulty, if specified.
    $results = array_filter($topN, function ($score) use ($name, $difficulty) {
      $valid = true;
      $valid &= !$name || $name === $score['name'];
      $valid &= !$difficulty || strtolower($difficulty) === $score['difficulty'];
      return $valid;
    });

    // Use uasort to preserve the rank, but still sort by the appropriate field.
    uasort($results, function ($a, $b) use ($sort_field) {
      if ($a[$sort_field] === $b[$sort_field]) {
        return 0;
      }

      if ($sort_field === 'score') {
        return ($a[$sort_field] > $b[$sort_field]) ? -1 : 1;
      } else if ($sort_field === 'difficulty') {
        $cmp = array( 'easy', 'medium', 'hard' );
        return (array_search($a[$sort_field], $cmp) >
                array_search($b[$sort_field], $cmp)) ? -1 : 1;
      } else {
        return ($a[$sort_field] < $b[$sort_field]) ? -1 : 1;
      } 
    });

    $view = $this->view($results, 200)
              ->setTemplate('results.html.twig');
    return $this->handleView($view);
  }
} 
