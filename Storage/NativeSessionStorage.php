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
    public function all(): array
    {
        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): bool
    {
        $positions = $this->all();
        unset($positions[$key]);

        $_SESSION[self::SESSION_KEY] = $positions;

        return $this->has($key) === false;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->all());
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): ?PositionInterface
    {
        if ($this->has($key)) {
            $positions = $this->all();

            return $positions[$key];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        $_SESSION[self::SESSION_KEY] = [];

        return empty($_SESSION[self::SESSION_KEY]);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, PositionInterface $position): bool
    {
        $positions = $this->all();
        $positions[$key] = $position;

        $_SESSION[self::SESSION_KEY] = $positions;

        return $this->has($key);
    }
}
