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
use Mindy\Cart\Storage\SymfonySessionStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SymfonySessionStorageTest extends TestCase
{
    public function testStorage()
    {
        $product = $this
            ->getMockBuilder(ProductInterface::class)
            ->getMock();
        $product->method('getPrice')->willReturn(100);
        $product->method('getUniqueId')->willReturn('foobar');

        $session = new Session(new MockArraySessionStorage());
        $storage = new SymfonySessionStorage($session);

        $this->assertSame([], $storage->all());
        $this->assertNull($storage->get('foo'));
        $this->assertFalse($storage->has('foo'));

        $position = new Position($product);
        $storage->set($position->getUniqueId(), $position);

        $this->assertCount(1, $storage->all());
        $this->assertNotNull($storage->get('091d0891163b8372709c08164bd4ee4b'));
        $this->assertTrue($storage->has('091d0891163b8372709c08164bd4ee4b'));

        $storage->clear();
        $this->assertCount(0, $storage->all());

        $storage->set($position->getUniqueId(), $position);
        $this->assertCount(1, $storage->all());

        $storage->remove('091d0891163b8372709c08164bd4ee4b');

        $this->assertCount(0, $storage->all());
    }
}
