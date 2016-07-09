<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ScoresGetController extends Controller {
  public function getAction($n, $name, $difficulty, $sort_field) {
    $format = $request->getRequestFormat();

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

      return ($a[$sort_field] < $b[$sort_field]) ? -1 : 1;
    });

    return $this->render("results.$format.twig", array(
      'results' => $results
    ));
  }
} 