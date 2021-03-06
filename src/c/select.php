<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace HH\Lib\C;

use namespace HH\Lib\_Private;
use namespace HH\Lib\Str;

/**
 * Returns the first value of the given Traversable for which the predicate
 * returns true, or null if no such value is found.
 */
<<__RxLocal>>
function find<T>(
  Traversable<T> $traversable,
  (function(T): bool) $value_predicate,
): ?T {
  foreach ($traversable as $value) {
    if ($value_predicate($value)) {
      return $value;
    }
  }
  return null;
}

/**
 * Returns the key of the first value of the given KeyedTraversable for which
 * the predicate returns true, or null if no such value is found.
 */
<<__RxLocal>>
function find_key<Tk, Tv>(
  KeyedTraversable<Tk, Tv> $traversable,
  (function(Tv): bool) $value_predicate,
): ?Tk {
  foreach ($traversable as $key => $value) {
    if ($value_predicate($value)) {
      return $key;
    }
  }
  return null;
}

/**
 * Returns the first element of the given Traversable, or null if the
 * Traversable is empty.
 *
 * - For non-empty Traversables, see `C\firstx`.
 * - For possibly null Traversables, see `C\nfirst`.
 * - For single-element Traversables, see `C\onlyx`.
 * - For Awaitables that yield Traversables, see `C\first_async`.
 */
<<__Rx>>
function first<T>(
  Traversable<T> $traversable,
): ?T {
  foreach ($traversable as $value) {
    return $value;
  }
  return null;
}

/**
 * Returns the first element of the given Traversable, or throws if the
 * Traversable is empty.
 *
 * - For possibly empty Traversables, see `C\first`.
 * - For possibly null Traversables, see `C\nfirst`.
 * - For single-element Traversables, see `C\onlyx`.
 * - For Awaitables that yield Traversables, see `C\firstx_async`.
 */
<<__Rx>>
function firstx<T>(
  Traversable<T> $traversable,
): T {
  foreach ($traversable as $value) {
    return $value;
  }
  invariant_violation('%s: Expected at least one element.', __FUNCTION__);
}

/**
 * Returns the first key of the given KeyedTraversable, or null if the
 * KeyedTraversable is empty.
 *
 * For non-empty Traversables, see `C\first_keyx`.
 */
<<__Rx>>
function first_key<Tk, Tv>(
  KeyedTraversable<Tk, Tv> $traversable,
): ?Tk {
  if ($traversable !== null) {
    foreach ($traversable as $key => $_) {
      return $key;
    }
  }
  return null;
}

/**
 * Returns the first key of the given KeyedTraversable, or throws if the
 * KeyedTraversable is empty.
 *
 * For possibly empty Traversables, see `C\first_key`.
 */
<<__Rx>>
function first_keyx<Tk, Tv>(
  KeyedTraversable<Tk, Tv> $traversable,
): Tk {
  foreach ($traversable as $key => $_) {
    return $key;
  }
  invariant_violation('%s: Expected at least one element.', __FUNCTION__);
}

/**
 * Returns the last element of the given Traversable, or null if the
 * Traversable is empty.
 *
 * - For non-empty Traversables, see `C\lastx`.
 * - For single-element Traversables, see `C\onlyx`.
 */
<<__RxLocal>>
function last<Tv>(
  Traversable<Tv> $traversable,
): ?Tv {
  if (_Private\is_any_array($traversable)) {
    return $traversable ? \end(&$traversable) : null;
  } else if ($traversable instanceof Iterable) {
    return $traversable->lastValue();
  }
  $value = null;
  foreach ($traversable as $value) {
  }
  return $value;
}

/**
 * Returns the last element of the given Traversable, or throws if the
 * Traversable is empty.
 *
 * - For possibly empty Traversables, see `C\last`.
 * - For single-element Traversables, see `C\onlyx`.
 */
<<__RxLocal>>
function lastx<Tv>(Traversable<Tv> $traversable): Tv {
  // There is no way to directly check whether an Iterable is empty,
  // so convert to Array. For Hack Collections, this should
  // be an O(1) operation. For other Iterables, it will be
  // O(n).
  if ($traversable instanceof Iterable) {
    $traversable = $traversable->toArray();
  }
  if (_Private\is_any_array($traversable)) {
    invariant($traversable, '%s: Expected non-empty input', __FUNCTION__);
    return \end(&$traversable);
  }
  $value = null;
  $did_iterate = false;
  foreach ($traversable as $value) {
    $did_iterate = true;
  }
  invariant($did_iterate, '%s: Expected non-empty input', __FUNCTION__);
  // UNSAFE_EXPR Hack thinks $value must be typed as ?Tv because
  // the foreach may not run. But the invariant above ensures it does.
  return $value;
}

/**
 * Returns the last key of the given KeyedTraversable, or null if the
 * KeyedTraversable is empty.
 *
 * For non-empty Traversables, see `C\last_keyx`.
 */
<<__RxLocal>>
function last_key<Tk, Tv>(
  KeyedTraversable<Tk, Tv> $traversable,
): ?Tk {
  if (_Private\is_any_array($traversable)) {
    if (!$traversable) {
      return null;
    }
    \end(&$traversable);
    return \key(&$traversable);
  } else if ($traversable instanceof KeyedIterable) {
    return $traversable->lastKey();
  }
  $key = null;
  foreach ($traversable as $key => $_) {
  }
  return $key;
}

/**
 * Returns the last key of the given KeyedTraversable, or throws if the
 * KeyedTraversable is empty.
 *
 * For possibly empty Traversables, see `C\last_key`.
 */
<<__RxLocal>>
function last_keyx<Tk, Tv>(
  KeyedTraversable<Tk, Tv> $traversable,
): Tk {
  $last_key = namespace\last_key($traversable);
  invariant($last_key !== null, '%s: Expected non-empty input', __FUNCTION__);
  return $last_key;
}

/**
 * Returns the first element of the given Traversable, or null if the
 * Traversable is null or empty.
 *
 * - For non-null Traversables, see `C\first`.
 * - For non-empty Traversables, see `C\firstx`.
 * - For single-element Traversables, see `C\onlyx`.
 */
<<__Rx>>
function nfirst<T>(
  ?Traversable<T> $traversable,
): ?T {
  if ($traversable !== null) {
    foreach ($traversable as $value) {
      return $value;
    }
  }
  return null;
}

/**
 * Returns the first and only element of the given Traversable, or throws if the
 * Traversable is empty.
 *
 * An optional format string (and format arguments) may be passed to specify
 * a custom message for the exception in the error case.
 *
 * For Traversables with more than one element, see `C\firstx`.
 */
<<__RxLocal>>
function onlyx<T>(
  Traversable<T> $traversable,
  ?Str\SprintfFormatString $format_string = null,
  mixed ...$format_args
): T {
  $first = true;
  $result = null;
  foreach ($traversable as $value) {
    invariant(
      $first,
      '%s',
      $format_string === null
        ? Str\format(
          'Expected exactly one element%s.',
          $traversable instanceof Container
            ? ' but got '.count($traversable)
            : '',
        )
        : \vsprintf($format_string, $format_args),
    );
    $result = $value;
    $first = false;
  }
  invariant(
    $first === false,
    '%s',
    $format_string === null
      ? 'Expected non-empty Traversable.'
      : \vsprintf($format_string, $format_args),
  );
  /* HH_IGNORE_ERROR[4110] $first is false implies $result is set to T */
  return $result;
}
