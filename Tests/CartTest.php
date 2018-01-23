<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Cart\Tests;

use Mindy\Cart\Cart;
use Mindy\Cart\Position;
use Mindy\Cart\PositionInterface;
use Mindy\Cart\ProductInterface;
use Mindy\Cart\Storage\CartStorageInterface;
use Mindy\Cart\Storage\SymfonySessionStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartTest extends TestCase
{
    protected function getProduct()
    {
        $product = $this
            ->getMockBuilder(ProductInterface::class)
            ->getMock();
        $product->method('getPrice')->willReturn(100);
        $product->method('getSku')->willReturn('foobar');

        return $product;
    }

    public function testPosition()
    {
        $product = $this->getProduct();
        $position = new Position($product);

        $this->assertInstanceOf(ProductInterface::class, $position->getProduct());
        $this->assertSame($product->getSku(), $position->generateUniqueId());

        $this->assertSame(100.00, $position->getPrice());
        $this->assertSame(1, $position->getQuantity());

        $position->setQuantity(2);
        $this->assertSame(200.00, $position->getPrice());
        $this->assertSame(2, $position->getQuantity());
    }

    public function testCart()
    {
        $session = new Session(new MockArraySessionStorage());
        $cart = new Cart(new SymfonySessionStorage($session));

        $this->assertInstanceOf(CartStorageInterface::class, $cart->getStorage());

        $this->assertSame(0.0, $cart->getPrice());
        $this->assertSame(0, $cart->getQuantity());

        $this->assertCount(0, $cart->getPositions());

        $product = $this->getProduct();
        $position = new Position($product);
        $cart->setPosition($position->generateUniqueId(), $position);

        $this->assertCount(1, $cart->getPositions());
        $this->assertTrue($cart->hasPosition('foobar'));
        $this->assertInstanceOf(PositionInterface::class, $cart->getPosition('foobar'));
        $this->assertSame(100.0, $cart->getPrice());
        $this->assertSame(1, $cart->getQuantity());

        $cart->removePosition('foobar');
        $this->assertCount(0, $cart->getPositions());

        $position = new Position($product);
        $cart->setPosition($position->generateUniqueId(), $position);
        $this->assertCount(1, $cart->getPositions());

        $cart->clear();
        $this->assertCount(0, $cart->getPositions());

        $position = new Position($product);
        $cart->setPosition($position->generateUniqueId(), $position);
        $this->assertSame(1, $cart->getQuantity());
        $cart->setPositionQuantity('foobar', 2);
        $this->assertSame(2, $cart->getQuantity());
    }
}
