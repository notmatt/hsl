<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

use namespace HH\Lib\Vec;
use function Facebook\FBExpect\expect;

/**
 * @emails oncall+hack
 */
final class VecAsyncTest extends PHPUnit_Framework_TestCase {

  public static function provideTestGen(): array<mixed> {
    return array(
      tuple(
        Vector {
          async {return 'the';},
          async {return 'quick';},
          async {return 'brown';},
          async {return 'fox';},
        },
        vec['the', 'quick', 'brown', 'fox'],
      ),
      tuple(
        Map {
          'foo' => async {return 1;},
          'bar' => async {return 2;},
        },
        vec[1, 2],
      ),
      tuple(
        HackLibTestTraversables::getIterator(array(
          async {return 'the';},
          async {return 'quick';},
          async {return 'brown';},
          async {return 'fox';},
        )),
        vec['the', 'quick', 'brown', 'fox'],
      ),
    );
  }

  /** @dataProvider provideTestGen */
  public function testFromAsync<Tv>(
    Traversable<Awaitable<Tv>> $awaitables,
    vec<Tv> $expected,
  ): void {
    /* HH_IGNORE_ERROR[5542] open source */
    \HH\Asio\join(async {
      $actual = await Vec\from_async($awaitables);
      expect($actual)->toBeSame($expected);
    });
  }

  public static function provideTestGenFilter(): array<mixed> {
    return array(
      tuple(
        array(
          '2' => 'two',
          '4' => 'four',
          6 => 'six',
          '8' => 'eight',
        ),
        async ($word) ==> strlen($word) % 2 === 1,
        vec['two', 'six', 'eight'],
      ),
      tuple(
        Vector {'the', 'quick', 'brown', 'fox', 'jumped', 'over'},
        async ($word) ==> strlen($word) % 2 === 0,
        vec['jumped', 'over'],
      ),
    );
  }

  /** @dataProvider provideTestGenFilter */
  public function testFilterAsync<Tv>(
    Container<Tv> $container,
    (function(Tv): Awaitable<bool>) $value_predicate,
    vec<Tv> $expected,
  ): void {
    /* HH_IGNORE_ERROR[5542] open source */
    \HH\Asio\join(async {
      $actual = await Vec\filter_async($container, $value_predicate);
      expect($actual)->toBeSame($expected);
    });
  }

  public static function provideTestGenMap(): array<mixed> {
    return array(
      tuple(
        Vector {'the', 'quick', 'brown', 'fox'},
        async ($word) ==> strrev($word),
        vec['eht', 'kciuq', 'nworb', 'xof'],
      ),
      tuple(
        HackLibTestTraversables::getIterator(
          array('the', 'quick', 'brown', 'fox'),
        ),
        async ($word) ==> strrev($word),
        vec['eht', 'kciuq', 'nworb', 'xof'],
      ),
    );
  }

  /** @dataProvider provideTestGenMap */
  public function testMapAsync<Tk, Tv>(
    Traversable<Tk> $keys,
    (function(Tk): Awaitable<Tv>) $async_func,
    vec<Tv> $expected,
  ): void {
    /* HH_IGNORE_ERROR[5542] open source */
    \HH\Asio\join(async {
      $actual = await Vec\map_async($keys, $async_func);
      expect($actual)->toBeSame($expected);
    });
  }
}
