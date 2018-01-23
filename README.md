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

```php
// Инициализация корзины
$session = new Session(new MockArraySessionStorage());
$cart = new Cart(new CartSessionStorage($session));

// Добавление позиции
$product = new SimpleProduct(['price' => 100, 'sku' => 'foo');

$position = new Position($product, 2);
$cart->setPosition($position->generateUniqueId(), $position);

assert(100.00 === $cart->getPrice());
assert(2 === $cart->getQuantity());

// Обновление количества товара в позиции
$position = $cart->getPosition('foo');
$position->setQuantity(1);
$cart->setPosition($position->generateUniqueId(), $position);
// или
$cart->setPositionQuantity('foo', 1);
```
