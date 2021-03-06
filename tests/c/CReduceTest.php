<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

use namespace HH\Lib\C;
use function Facebook\FBExpect\expect;

/**
 * @emails oncall+hack
 */
final class CReduceTest extends PHPUnit_Framework_TestCase {

  public static function provideTestReduce(): array<mixed> {
    return array(
      tuple(
        Set {'the', ' quick', ' brown', ' fox'},
        ($a, $s) ==> $a.$s,
        '',
        'the quick brown fox',
      ),
      tuple(
        HackLibTestTraversables::getIterator(
          array('the', ' quick', ' brown', ' fox'),
        ),
        ($a, $s) ==> $a.$s,
        '',
        'the quick brown fox',
      ),
      tuple(
        array('the', 'quick', 'brown', 'fox'),
        ($a, $s) ==> $a->add($s),
        Vector {},
        Vector {'the', 'quick', 'brown', 'fox'},
      ),
    );
  }

  /** @dataProvider provideTestReduce */
  public function testReduce<Tv, Ta>(
    Traversable<Tv> $traversable,
    (function(Ta, Tv): Ta) $accumulator,
    Ta $initial,
    Ta $expected,
  ): void {
    expect(C\reduce($traversable, $accumulator, $initial))->toEqual($expected);
  }
}
