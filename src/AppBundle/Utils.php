<?php

namespace AppBundle;

class Utils {
  /* 
   * Generate all permutations of elements from a set up to a given length.
   */
  public function permutations($set, $length) {
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
