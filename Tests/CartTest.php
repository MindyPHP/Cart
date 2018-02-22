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
use Mindy\Cart\Utils;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Util;
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
        $cart->add($product);

        $this->assertCount(1, $cart->all());
        $this->assertTrue($cart->has($product));
        $this->assertInstanceOf(PositionInterface::class, $cart->find($product));
        $this->assertSame(100.0, $cart->getPrice());
        $this->assertSame(1, $cart->getQuantity());

        $cart->add($product);
        $this->assertSame(2, $cart->getQuantity());

        $cart->add($product, 5, [], true);
        $this->assertSame(5, $cart->getQuantity());

        $cart->remove($product);
        $this->assertCount(0, $cart->all());

        $cart->add($product);
        $this->assertCount(1, $cart->all());

        $cart->clear();
        $this->assertCount(0, $cart->all());

        $cart->add($product);
        $this->assertSame(1, $cart->getQuantity());

        $uniqueId = Utils::doGenerateUniqueId($product);
        $position = $cart->find($product);
        $position->setQuantity(5);
        $cart->replace($uniqueId, $position);
        $this->assertSame(5, $cart->getQuantity());

        $cart->setQuantity($uniqueId, 10);
        $this->assertSame(15, $cart->getQuantity());

        $cart->setQuantity(Utils::doGenerateUniqueId($product), 10, true);
        $this->assertSame(10, $cart->getQuantity());
    }
}
