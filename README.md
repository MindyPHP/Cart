# Компонент работы с корзиной покупателя

[![Build Status](https://travis-ci.org/MindyPHP/Cart.svg?branch=master)](https://travis-ci.org/MindyPHP/Cart)
[![codecov](https://codecov.io/gh/MindyPHP/Cart/branch/master/graph/badge.svg)](https://codecov.io/gh/MindyPHP/Cart)
[![Latest Stable Version](https://poser.pugx.org/mindy/cart/v/stable.svg)](https://packagist.org/packages/mindy/cart)
[![Total Downloads](https://poser.pugx.org/mindy/cart/downloads.svg)](https://packagist.org/packages/mindy/cart)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FMindyPHP%2FCart.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FMindyPHP%2FCart?ref=badge_shield)

## Установка

```bash
composer require mindy/cart --prefer-dist
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

Использование

```php
<?php

// Добавление позиции
$product = new SimpleProduct(['price' => 100, 'uniqueId' => 'foo']);
$quantity = 2;
$options = ['cpu' => 'xeon', 'memory' => '4'];
$cart->add($product, $quantity, $options);
assert(1, count($cart->all()));

$cart->add($product, $quantity, $options);
assert(2, count($cart->all()));

$cart->add($product, $quantity, $options, true); // Замена позиции
assert(1, count($cart->all()));

// Проверка наличия позиции
$cart->has($product, $options);

// Удаление позиции
$cart->remove($product, $options);

// Поиск позиции
$position = $cart->find($product, $options);

// Все позиции
$cart->all();

// Очистка
$cart->clear();

// Добавление количества
$cart->setQuantity($key, 5);

// Изменение количества
$cart->setQuantity($key, 5, true);

// Изменение количества - альтернативный вариант
$position = $cart->get($key);
$position->setQuantity($position->getQuantity() + 2);
$cart->replace($key, $position);
```


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FMindyPHP%2FCart.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FMindyPHP%2FCart?ref=badge_large)
