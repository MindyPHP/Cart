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
use Mindy\Cart\Storage\CartStorageInterface;
use Mindy\Cart\Utils;
use PHPUnit\Framework\TestCase;

abstract class AbstractSessionStorageTest extends TestCase
{
    abstract public function getStorage(): CartStorageInterface;

    public function testStorage()
    {
        $product = $this
            ->getMockBuilder(ProductInterface::class)
            ->getMock();
        $product->method('getPrice')->willReturn(100);
        $product->method('getUniqueId')->willReturn('foobar');

        $storage = $this->getStorage();

        $this->assertSame([], $storage->all());
        $this->assertNull($storage->get('foo'));
        $this->assertFalse($storage->has('foo'));

        $uniqueId = Utils::doGenerateUniqueId($product);
        $position = new Position($product);
        $storage->set($uniqueId, $position);

        $this->assertCount(1, $storage->all());
        $this->assertNotNull($storage->get($uniqueId));
        $this->assertTrue($storage->has($uniqueId));

        $storage->clear();
        $this->assertCount(0, $storage->all());

        $storage->set($uniqueId, $position);
        $this->assertCount(1, $storage->all());

        $storage->remove($uniqueId);

        $this->assertCount(0, $storage->all());
    }
}
