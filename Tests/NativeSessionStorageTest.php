<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Cart\Tests;

use Mindy\Cart\Position;
use Mindy\Cart\ProductInterface;
use Mindy\Cart\Storage\NativeSessionStorage;
use PHPUnit\Framework\TestCase;

class NativeSessionStorageTest extends TestCase
{
    public function testStorage()
    {
        $product = $this
            ->getMockBuilder(ProductInterface::class)
            ->getMock();
        $product->method('getPrice')->willReturn(100);
        $product->method('getUniqueId')->willReturn('foobar');

        $storage = new NativeSessionStorage();

        $this->assertSame([], $storage->getPositions());
        $this->assertNull($storage->getPosition('foo'));
        $this->assertFalse($storage->hasPosition('foo'));

        $position = new Position($product);
        $storage->setPosition($position->generateUniqueId(), $position);

        $this->assertCount(1, $storage->getPositions());
        $this->assertNotNull($storage->getPosition('091d0891163b8372709c08164bd4ee4b'));
        $this->assertTrue($storage->hasPosition('091d0891163b8372709c08164bd4ee4b'));

        $storage->clear();
        $this->assertCount(0, $storage->getPositions());

        $storage->setPosition($position->generateUniqueId(), $position);
        $this->assertCount(1, $storage->getPositions());

        $storage->removePosition('091d0891163b8372709c08164bd4ee4b');

        $this->assertCount(0, $storage->getPositions());
    }
}
