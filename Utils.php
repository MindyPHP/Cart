<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Mindy\Cart;

class Utils
{
    public static function doGenerateUniqueId(ProductInterface $product, ?array $data = []): string
    {
        if ($data === null) {
            $data = [];
        }

        return sprintf(
            "%s_%s",
            $product->getUniqueId(),
            md5(serialize(self::sort($data)))
        );
    }

    public static function sort($data): array
    {
        ksort($data);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::sort($value);
            }
        }

        return $data;
    }
}
