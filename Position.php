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
     * @var array
     */
    protected $options = [];
    /**
     * @var int
     */
    protected $quantity = 1;

    /**
     * Position constructor.
     * @param ProductInterface $product
     * @param int $quantity
     * @param array $options
     */
    public function __construct(ProductInterface $product, int $quantity = 1, array $options = [])
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->options = $options;
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
        return md5(sprintf("%s:%s", $this->product->getUniqueId(), serialize($this->options)));
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Return array of options for product
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
