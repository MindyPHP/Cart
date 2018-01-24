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
        $product->method('getUniqueId')->willReturn('foobar');

        return $product;
    }

    public function testCart()
    {
        $session = new Session(new MockArraySessionStorage());
        $cart = new Cart(new SymfonySessionStorage($session));

        $this->assertSame(0.0, $cart->getPrice());
        $this->assertSame(0, $cart->getQuantity());

        $this->assertCount(0, $cart->all());

        $product = $this->getProduct();
        $position = new Position($product);
        $cart->set($position->getUniqueId(), $position);

        $this->assertCount(1, $cart->all());
        $this->assertTrue($cart->has('091d0891163b8372709c08164bd4ee4b'));
        $this->assertInstanceOf(PositionInterface::class, $cart->get('091d0891163b8372709c08164bd4ee4b'));
        $this->assertSame(100.0, $cart->getPrice());
        $this->assertSame(1, $cart->getQuantity());

        $cart->remove('091d0891163b8372709c08164bd4ee4b');
        $this->assertCount(0, $cart->all());

        $position = new Position($product);
        $cart->set($position->getUniqueId(), $position);
        $this->assertCount(1, $cart->all());

        $cart->clear();
        $this->assertCount(0, $cart->all());

        $position = new Position($product);
        $cart->set($position->getUniqueId(), $position);
        $this->assertSame(1, $cart->getQuantity());
        $cart->setQuantity('091d0891163b8372709c08164bd4ee4b', 2);
        $this->assertSame(2, $cart->getQuantity());
    }
}
