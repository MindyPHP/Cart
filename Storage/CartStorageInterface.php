<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Cart\Storage;

use Mindy\Cart\PositionInterface;

interface CartStorageInterface
{
    /**
     * @return array|PositionInterface[]
     */
    public function all(): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function remove(string $key): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param PositionInterface $position
     *
     * @return bool
     */
    public function set(string $key, PositionInterface $position): bool;

    /**
     * @param string $key
     *
     * @return PositionInterface|null
     */
    public function get(string $key): ?PositionInterface;

    /**
     * Remove all positions from cart
     *
     * @return bool
     */
    public function clear(): bool;
}
