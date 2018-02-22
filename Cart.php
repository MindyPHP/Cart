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
    public function clear(): bool
    {
        return $this->storage->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function getStorage(): CartStorageInterface
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function addProduct(ProductInterface $product, int $quantity = 1, array $options = [], bool $replace = false): bool
    {
        if ($replace) {
            $this->removeProduct($product, $options);
        }

        if ($this->hasProduct($product, $options)) {
            $position = $this->findPosition($product, $options);
            $position->setQuantity($position->getQuantity() + $quantity);
        } else {
            $position = $this->createPosition($product, $quantity, $options);
        }

        return $this->getStorage()->set(
            $this->doGenerateUniqueId($product, $options),
            $position
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createPosition(ProductInterface $product, int $quantity, array $options = []): PositionInterface
    {
        return new Position($product, $quantity, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function hasProduct(ProductInterface $product, array $options = []): bool
    {
        return $this->getStorage()->has($this->doGenerateUniqueId($product, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(string $key): ?PositionInterface
    {
        return $this->getStorage()->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function findPosition(ProductInterface $product, array $options = []): ?PositionInterface
    {
        return $this->getPosition($this->doGenerateUniqueId($product, $options));
    }

    /**
     * @param ProductInterface $product
     * @param array|null $options
     *
     * @return string
     */
    private function doGenerateUniqueId(ProductInterface $product, array $options = []): string
    {
        return Utils::doGenerateUniqueId($product, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity(): int
    {
        return array_sum(array_map(function (PositionInterface $position) {
            return $position->getQuantity();
        }, $this->all()));
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice(): float
    {
        return array_sum(array_map(function (PositionInterface $position) {
            return $position->getPrice();
        }, $this->all()));
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->getStorage()->all();
    }

    /**
     * {@inheritdoc}
     */
    public function removeProduct(ProductInterface $product, ?array $options = []): bool
    {
        return $this->getStorage()->remove($this->doGenerateUniqueId($product, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function replacePosition(string $key, PositionInterface $position): bool
    {
        return $this->getStorage()->set($key, $position);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity(string $key, int $quantity, bool $replace = false): bool
    {
        $position = $this->getStorage()->get($key);
        if ($replace) {
            $position->setQuantity($quantity);
        } else {
            $position->setQuantity($position->getQuantity() + $quantity);
        }

        return $this->getStorage()->set($key, $position);
    }

    /**
     * {@inheritdoc}
     */
    public function hasPosition(string $key): bool
    {
        return $this->getStorage()->has($key);
    }

    /**
     * {@inheritdoc}
     */
    public function removePosition(string $key): bool
    {
        return $this->getStorage()->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function replaceProduct(ProductInterface $product, int $quantity, array $data = []): bool
    {
        return $this->setQuantity($this->doGenerateUniqueId($product, $data), $quantity, true);
    }
}
