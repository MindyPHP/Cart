<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Mindy\Cart\Tests;

use Mindy\Cart\ProductInterface;
use Mindy\Cart\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function testSort()
    {
        $expected = ['a' => 1, 'b' => 1];
        $this->assertSame($expected, Utils::sort([
            'b' => 1,
            'a' => 1,
        ]));

        $expected = ['a' => 1, 'b' => ['a' => 1, 'b' => 2]];
        $this->assertSame($expected, Utils::sort([
            'b' => [
                'b' => 2,
                'a' => 1,
            ],
            'a' => 1,
        ]));

        $expected = ['a' => 'a', 'b' => ['a' => 'a', 'b' => ['a' => 'a', 'b' => 'b', 'c' => 'c']]];
        $this->assertSame($expected, Utils::sort([
            'b' => [
                'b' => [
                    'a' => 'a',
                    'c' => 'c',
                    'b' => 'b',
                ],
                'a' => 'a',
            ],
            'a' => 'a',
        ]));
    }

    public function testGenerateUniqueId()
    {
        $product = $this
            ->getMockBuilder(ProductInterface::class)
            ->getMock();
        $product->method('getPrice')->willReturn(100);
        $product->method('getUniqueId')->willReturn('foobar');

        $this->assertSame(Utils::doGenerateUniqueId($product), Utils::doGenerateUniqueId($product, null));
        $this->assertSame(Utils::doGenerateUniqueId($product), Utils::doGenerateUniqueId($product, []));

        $this->assertSame(
            Utils::doGenerateUniqueId($product, [
                'disk' => [
                    'type' => 'ssd',
                    'size' => 20
                ],
                'cpu' => 'xeon'
            ]),
            Utils::doGenerateUniqueId($product, [
                'cpu' => 'xeon',
                'disk' => [
                    'size' => 20,
                    'type' => 'ssd',
                ],
            ])
        );
    }
}
