<?php

namespace AppBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ScoresGetLoader extends Loader {
  private $loaded = false;

  public function load($resource, $type = null) {
    if ($this->loaded) {
      throw new \RuntimeException('Do not add "ScoresGetLoader" twice.');
    }

    $routes = new RouteCollection();

    // We have four path components which can occur in any order and all map
    // to the same controller. We use this loader to automatically compose 
    // these components and programatically build all routes.
    $controller = 'AppBundle:ScoresGetController:get';

    $components = array(
      array(
        'path' => '/num/{n}',
        'defaults' => array( 'n' => '10' ),
        'requirements' => array( 'n' => '\d+' )
      ),
      array(
        'path' => '/name/{name}',
        'defaults' => array( 'name' => '' ),
        'requirements' => array( 'name' => '\w+' )
      ),
      array(
        'path' => '/difficulty/{difficulty}',
        'defaults' => array( 'difficulty' => '' ),
        'requirements' => array( 'difficulty' => '(?i)easy|medium|hard|^$' )
      ),
      array(
        'path' => '/sort_by/{sort_field}',
        'defaults' => array( 'sort_field' => 'score' ),
        'requirements' => array( 'sort_field' => 'name|score|difficulty' )
      )
    );

  }

  /* 
   * Generate all permutations of elements from a set up to a given length.
   */
  private function permutations($set, $length) {
    // If generating single-length elements, just wrap each element as an array
    // and return.
    if ($length === 1) {
      $wrapped = array();

      for ($i = 0; $i < sizeof($set); $i++) {
        array_push($wrapped, array($set[$i]));
      }

      return $wrapped;
    }

    // Length greater than 1, recurse.
    $subperms = permutations($set, $length - 1);
    // Carry forward elements from recursion.
    $perms = $subperms;

    // Take each element from the set, insert into each position in each 
    // subpermutation where the subpermutation doesn't already include said 
    // element.
    for ($i = 0; $i < sizeof($set); $i++) {
      for ($j = 0; $j < sizeof($subperms); $j++) {
        if (!in_array($set[$i], $subperms[$j])) {
          for ($k = 0; $k <= sizeof($subperms[$j]); $k++) {
            $copy = $subperms[$j];
            array_splice($copy, $k, 0, $set[$i]);
            // Don't duplicate permutations.
            if (!in_array($copy, $perms)) {
              array_push($perms, $copy);
            }
          }
        }
      }
    }

    return $perms;
  }
}
