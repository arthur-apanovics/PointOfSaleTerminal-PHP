<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Models;

use InvalidArgumentException;
use PointOfSaleTerminal\Common\ProductValidationHelper;

class BulkPrice
{
    public function __construct(
        public int $threshold,
        public int $price
    ) {
        if ($threshold <= 0) {
            throw new InvalidArgumentException(
                "Bulk threshold cannot be less than or equal to zero"
            );
        }

        ProductValidationHelper::validate_price_or_throw($price);
    }
}