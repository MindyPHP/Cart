<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Cart;

class Position implements PositionInterface
{
    /**
     * @var ProductInterface
     */
    protected $product;
    /**
     * @var int
     */
    protected $quantity = 1;

    /**
     * Position constructor.
     * @param ProductInterface $product
     * @param int $quantity
     */
    public function __construct(ProductInterface $product, int $quantity = 1)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice(): float
    {
        return (float) $this->product->getPrice() * $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    /**
     * {@inheritdoc}
     */
    public function generateUniqueId(): string
    {
        return $this->product->getSku();
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }
}
