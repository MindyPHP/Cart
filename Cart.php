<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Cart;

use Mindy\Cart\Storage\CartStorageInterface;

class Cart implements CartInterface
{
    /**
     * @var CartStorageInterface
     */
    protected $storage;

    /**
     * Cart constructor.
     *
     * @param CartStorageInterface $storage
     */
    public function __construct(CartStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getPositions(): array
    {
        return $this->storage->getPositions();
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity(): int
    {
        return array_sum(array_map(function (PositionInterface $position) {
            return $position->getQuantity();
        }, $this->getPositions()));
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return array_sum(array_map(function (PositionInterface $position) {
            return $position->getPrice();
        }, $this->getPositions()));
    }

    /**
     * @param $key
     * @param PositionInterface $position
     */
    public function setPosition(string $key, PositionInterface $position)
    {
        $this->storage->setPosition($key, $position);
    }

    /**
     * {@inheritdoc}
     */
    public function removePosition(string $key)
    {
        $this->storage->removePosition($key);
    }

    /**
     * {@inheritdoc}
     */
    public function hasPosition(string $key): bool
    {
        return $this->storage->hasPosition($key);
    }

    /**
     * @return CartStorageInterface
     */
    public function getStorage(): CartStorageInterface
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(string $key)
    {
        return $this->storage->getPosition($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->storage->clear();
    }

    /**
     * @param string $key
     * @param int $quantity
     */
    public function setPositionQuantity(string $key, int $quantity)
    {
        $position = $this->getPosition($key);
        $position->setQuantity($quantity);

        $this->storage->setPosition($key, $position);
    }
}
