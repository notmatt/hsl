<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace HH\Lib\Vec;

/**
 * Returns a new vec formed by concatenating the given Traversables together.
 *
 * For a variable number of Traversables, see `Vec\flatten()`.
 */
<<__Rx>>
function concat<Tv>(
  Traversable<Tv> $first,
  Traversable<Tv> ...$rest
): vec<Tv> {
  $result = vec($first);
  foreach ($rest as $traversable) {
    foreach ($traversable as $value) {
      $result[] = $value;
    }
  }
  return $result;
}
