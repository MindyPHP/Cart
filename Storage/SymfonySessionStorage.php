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
    public function getPositions(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }

    /**
     * {@inheritdoc}
     */
    public function removePosition(string $key)
    {
        $positions = $this->getPositions();
        unset($positions[$key]);

        $this->session->set(self::SESSION_KEY, $positions);
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
        $this->session->remove(self::SESSION_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition(string $key, PositionInterface $position)
    {
        $positions = $this->getPositions();
        $positions[$key] = $position;

        $this->session->set(self::SESSION_KEY, $positions);
    }
}
