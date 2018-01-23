# Компонент работы с корзиной покупателя

[![Build Status](https://travis-ci.org/MindyPHP/Cart.svg?branch=master)](https://travis-ci.org/MindyPHP/Cart)
[![codecov](https://codecov.io/gh/MindyPHP/Cart/branch/master/graph/badge.svg)](https://codecov.io/gh/MindyPHP/Cart)
[![Latest Stable Version](https://poser.pugx.org/mindy/cart/v/stable.svg)](https://packagist.org/packages/mindy/cart)
[![Total Downloads](https://poser.pugx.org/mindy/cart/downloads.svg)](https://packagist.org/packages/mindy/cart)

## Установка

```bash
composer require mindy/cart:"~1.0" --prefer-dist
```

## Использование

### Инициализация корзины

На текущий момент доступно 2 хранилища `SymfonySessionStorage` и `NativeSessionStorage`.

```php
// Symfony
$session = new Session(new MockArraySessionStorage());
$cart = new Cart(new SymfonySessionStorage($session));
```

```php
// Native $_SESSION
$cart = new Cart(new NativeSessionStorage());
```

### Добавление позиции

Создание простого класса товара

```php
<?php

declare(strict_types=1);

use Mindy\Cart\ProductInterface;

class SimpleProduct implements ProductInterface
{
    /**
     * @var float
     */
    protected $price;
    /**
     * @var string
     */
    protected $uniqueId;

    /**
     * SimpleProduct constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice(): float
    {
        return (float) $this->price;
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }
}
```

Первый входящий аргумент это товар типа `ProductInterface`, далее количество товара и опции.

```php
use Mindy\Cart\Position;

$product = new SimpleProduct(['price' => 100, 'uniqueId' => 'foo']);
$position = new Position($product, 1, ['cpu' => 'xeon', 'memory' => '4']);
$cart->setPosition($position->generateUniqueId(), $position);
```

### Удаление позиции

```php
$cart->removePosition($key);
```

### Изменение количества

```php
// Обновление количества товара в позиции
$position = $cart->getPosition('foo');
$position->setQuantity(1);
$cart->setPosition($position->generateUniqueId(), $position);
// или
$cart->setPositionQuantity('foo', 1);
```

### Опции 

```php
<?php

use Mindy\Cart\Position;

$product = new SimpleProduct(['price' => 100, 'uniqueId' => 'foo']);

$position = new Position($product, 1, ['cpu' => 'xeon', 'memory' => '4']);
$cart->setPosition($position->generateUniqueId(), $position);

$position = new Position($product, 1, ['cpu' => 'xeon', 'memory' => '2']);
$cart->setPosition($position->generateUniqueId(), $position);

assert(2 === $cart->getQuantity());
```
