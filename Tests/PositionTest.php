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
use Mindy\Cart\Utils;
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

        $this->assertSame(100.00, $position->getPrice());
        $this->assertSame(1, $position->getQuantity());
        $this->assertSame([], $position->getOptions());

        $position->setQuantity(2);
        $this->assertSame(100.00, $position->getPrice());
        $this->assertSame(200.00, $position->getTotalPrice());
        $this->assertSame(2, $position->getQuantity());
        $this->assertSame([], $position->getOptions());
    }

    public function testPositionOptions()
    {
        $product = $this->getProduct();
        $uniqueId = Utils::doGenerateUniqueId($product);
        $position = new Position($product, 1, ['memory' => '2', 'cpu' => 'intel xeon']);

        $this->assertInstanceOf(ProductInterface::class, $position->getProduct());

        $this->assertSame(100.00, $position->getPrice());
        $this->assertSame(1, $position->getQuantity());
        $this->assertSame(['memory' => '2', 'cpu' => 'intel xeon'], $position->getOptions());

        $position = new Position($product, 1, ['memory' => '4', 'cpu' => 'intel xeon']);

        $this->assertInstanceOf(ProductInterface::class, $position->getProduct());
        $this->assertSame('foobar_40cd750bba9870f18aada2478b24840a', $uniqueId);

        $this->assertSame(100.00, $position->getPrice());
        $this->assertSame(1, $position->getQuantity());
        $this->assertSame(['memory' => '4', 'cpu' => 'intel xeon'], $position->getOptions());
    }
}
