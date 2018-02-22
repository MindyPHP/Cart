<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Cart\Tests;

use Mindy\Cart\Storage\CartStorageInterface;
use Mindy\Cart\Storage\NativeSessionStorage;

class NativeSessionStorageTest extends AbstractSessionStorageTest
{
    public function getStorage(): CartStorageInterface
    {
        return new NativeSessionStorage();
    }
}
