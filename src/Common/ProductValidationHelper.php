<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Common;

use InvalidArgumentException;
use PointOfSaleTerminal\Models\BulkProduct;
use PointOfSaleTerminal\Models\Product;

class ProductValidationHelper
{
    /**
     * @param Product[]|BulkProduct[] $products
     * @return bool
     */
    public static function has_duplicates(array $products): bool
    {
        $seen = [];
        foreach ($products as $product)
        {
            if (in_array($product->code, $seen)){
                return true;
            }

            $seen[] = $product->code;
        }

        return false;
    }

    public static function validate_product_code_or_throw(string $code): void
    {
        if (empty($code) || str_contains($code, ' ')) {
            throw new InvalidArgumentException("Product code must not contain spaces and not be empty");
        }
    }

    public static function validate_price_or_throw(int $price): void
    {
        if ($price <= 0) {
            throw new InvalidArgumentException("Price cannot be zero or negative");
        }
    }
}