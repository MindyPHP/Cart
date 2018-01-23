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

interface CartInterface extends CartStorageInterface
{
    /**
     * @param string $key
     * @param int $quantity
     */
    public function setPositionQuantity(string $key, int $quantity);

    /**
     * @return int
     */
    public function getQuantity(): int;

    /**
     * @return float
     */
    public function getPrice(): float;
}
