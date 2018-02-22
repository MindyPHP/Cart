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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SymfonySessionStorage implements CartStorageInterface
{
    const SESSION_KEY = '__cart';

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * CartSessionStorage constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): bool
    {
        $positions = $this->all();
        unset($positions[$key]);

        $this->session->set(self::SESSION_KEY, $positions);

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
    public function clear()
    {
        $this->session->remove(self::SESSION_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, PositionInterface $position): bool
    {
        $positions = $this->all();
        $positions[$key] = $position;

        $this->session->set(self::SESSION_KEY, $positions);

        return $this->has($key);
    }
}
