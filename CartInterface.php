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

interface CartInterface
{
    /**
     * @param ProductInterface $product
     * @param array|null $options
     *
     * @return bool
     */
    public function remove(ProductInterface $product, array $options = []): bool;

    /**
     * @param ProductInterface $product
     * @param int $quantity
     * @param array|null $options
     * @param bool $replace
     *
     * @return bool
     */
    public function add(ProductInterface $product, int $quantity = 1, array $options = [], bool $replace = false): bool;


    /**
     * @param ProductInterface $product
     * @param array|null $options
     *
     * @return bool
     */
    public function has(ProductInterface $product, array $options = []): bool;

    /**
     * @param string $key
     *
     * @return PositionInterface|null
     */
    public function get(string $key): ?PositionInterface;

    /**
     * @param ProductInterface $product
     * @param array|null $options
     *
     * @return PositionInterface|null
     */
    public function find(ProductInterface $product, array $options = []): ?PositionInterface;

    /**
     * @return array|[]PositionInterface
     */
    public function all(): array;

    /**
     * @return bool
     */
    public function clear(): bool;

    /**
     * @return int
     */
    public function getQuantity(): int;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @param string $key
     * @param PositionInterface $position
     *
     * @return bool
     */
    public function replace(string $key, PositionInterface $position): bool;

    /**
     * @param string $position
     * @param int $quantity
     * @param bool $replace
     *
     * @return bool
     */
    public function setQuantity(string $position, int $quantity, bool $replace = false): bool;

    /**
     * @return CartStorageInterface
     */
    public function getStorage(): CartStorageInterface;
}
