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

class NativeSessionStorage implements CartStorageInterface
{
    const SESSION_KEY = '__cart';

    public function __construct()
    {
        if (false === isset($_SESSION)) {
            $_SESSION = [];
        }

        if (false === array_key_exists(self::SESSION_KEY, $_SESSION)) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPositions(): array
    {
        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * {@inheritdoc}
     */
    public function removePosition(string $key)
    {
        $positions = $this->getPositions();
        unset($positions[$key]);

        $_SESSION[self::SESSION_KEY] = $positions;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPosition(string $key): bool
    {
        return array_key_exists($key, $this->getPositions());
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(string $key)
    {
        if ($this->hasPosition($key)) {
            $positions = $this->getPositions();

            return $positions[$key];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $_SESSION[self::SESSION_KEY] = [];
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition(string $key, PositionInterface $position)
    {
        $positions = $this->getPositions();
        $positions[$key] = $position;

        $_SESSION[self::SESSION_KEY] = $positions;
    }
}
