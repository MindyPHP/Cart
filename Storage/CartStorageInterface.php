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
     * @param string            $key
     * @param PositionInterface $position
     */
    public function setPosition(string $key, PositionInterface $position);

    /**
     * @return array|PositionInterface[]
     */
    public function getPositions(): array;

    /**
     * @param string $key
     */
    public function removePosition(string $key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasPosition(string $key): bool;

    /**
     * @param $key
     *
     * @return PositionInterface
     */
    public function getPosition(string $key);

    /**
     * Remove all positions from cart
     */
    public function clear();
}
