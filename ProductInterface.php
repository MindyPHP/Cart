<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Cart;

interface ProductInterface
{
    /**
     * @return float
     */
    public function getPrice();

    /**
     * @return string
     */
    public function getSku();
}
