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
use Mindy\Cart\CartInterface;
use Mindy\Cart\PositionInterface;
use Mindy\Cart\Storage\CartStorageInterface;
use Mindy\Cart\Storage\SymfonySessionStorage;
use Mindy\Cart\Utils;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartTest extends AbstractSessionStorageTest
{
    protected function createCart(): CartInterface
    {
        return new Cart($this->getStorage());
    }

    public function testCart()
    {
        $uniqueId = Utils::doGenerateUniqueId($this->product);

        $cart = $this->createCart();

        $this->assertSame(0.0, $cart->getPrice());
        $this->assertSame(0, $cart->getQuantity());
        $this->assertCount(0, $cart->all());

        $cart->addProduct($this->product);
        $this->assertCount(1, $cart->all());
        $this->assertSame(100.0, $cart->getPrice());
        $this->assertSame(1, $cart->getQuantity());

        $this->assertTrue($cart->hasProduct($this->product));
        $this->assertTrue($cart->hasPosition($uniqueId));

        $this->assertInstanceOf(PositionInterface::class, $cart->findPosition($this->product));

        $cart->addProduct($this->product, 2);
        $this->assertSame(3, $cart->getQuantity());
        $this->assertSame(300.0, $cart->getPrice());

        $cart->addProduct($this->product, 2, [], true);
        $this->assertSame(2, $cart->getQuantity());

        $cart->removeProduct($this->product);
        $this->assertCount(0, $cart->all());

        $cart->clear();
        $this->assertCount(0, $cart->all());

        $cart->addProduct($this->product);
        $position = $cart->findPosition($this->product);
        $position->setQuantity(5);
        $cart->replacePosition($uniqueId, $position);
        $this->assertSame(5, $cart->getQuantity());

        $cart->setQuantity($uniqueId, 10);
        $this->assertSame(15, $cart->getQuantity());

        $cart->setQuantity($uniqueId, 10, true);
        $this->assertSame(10, $cart->getQuantity());

        $cart->removePosition($uniqueId);
        $this->assertSame(0, $cart->getQuantity());
    }

    public function getStorage(): CartStorageInterface
    {
        $session = new Session(new MockArraySessionStorage());
        return new SymfonySessionStorage($session);
    }
}
