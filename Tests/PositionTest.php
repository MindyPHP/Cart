<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 23/01/2018
 * Time: 18:05
 */

namespace Mindy\Cart\Tests;

use Mindy\Cart\Position;
use Mindy\Cart\ProductInterface;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
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

    public function testPosition()
    {
        $product = $this->getProduct();
        $position = new Position($product);

        $this->assertInstanceOf(ProductInterface::class, $position->getProduct());
        $this->assertSame('091d0891163b8372709c08164bd4ee4b', $position->getUniqueId());

        $this->assertSame(100.00, $position->getPrice());
        $this->assertSame(1, $position->getQuantity());
        $this->assertSame([], $position->getOptions());

        $position->setQuantity(2);
        $this->assertSame(200.00, $position->getPrice());
        $this->assertSame(2, $position->getQuantity());
        $this->assertSame([], $position->getOptions());
    }

    public function testPositionOptions()
    {
        $product = $this->getProduct();
        $position = new Position($product, 1, ['memory' => '2', 'cpu' => 'intel xeon']);

        $this->assertInstanceOf(ProductInterface::class, $position->getProduct());
        $this->assertSame('a971f3a2fe71378f3a97dc454f770dc4', $position->getUniqueId());

        $this->assertSame(100.00, $position->getPrice());
        $this->assertSame(1, $position->getQuantity());
        $this->assertSame(['memory' => '2', 'cpu' => 'intel xeon'], $position->getOptions());

        $position = new Position($product, 1, ['memory' => '4', 'cpu' => 'intel xeon']);

        $this->assertInstanceOf(ProductInterface::class, $position->getProduct());
        $this->assertSame('30725a68944225e92af286ff31461711', $position->getUniqueId());

        $this->assertSame(100.00, $position->getPrice());
        $this->assertSame(1, $position->getQuantity());
        $this->assertSame(['memory' => '4', 'cpu' => 'intel xeon'], $position->getOptions());
    }
}
