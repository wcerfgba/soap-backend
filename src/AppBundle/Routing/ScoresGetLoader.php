<?php

namespace AppBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use AppBundle\Utils;

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

    $indicesList = Utils.permutations(range(0, sizeof($components) - 1),
                                      sizeof($components));
    foreach ($indicesList as $i => $indices) {
      $routeName = "get_$i";
      $parts = array_map(function ($i) { return $components[$i]; }, $indices);
      $path = '';
      $defaults = array( '_controller' => $controller );
      $requirements = array();

      foreach ($parts as $part) {
        $path = $path . $part['path'];
        $defaults = array_merge($defaults, $part['defaults']);
        $requirements = array_merge($requirements, $part['requirements']);
      }

      $route = new Route($path, $defaults, $requirements);
      $routes->add($routeName, $route);
    }

    $this->loaded = true;
    return $routes;
  }
}
