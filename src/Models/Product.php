<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Models;

use PointOfSaleTerminal\Common\ProductValidationHelper;

/**
 * Represents a regular product code to price mapping.
 */
class Product
{
	/**
	 * @param string $code Product code
	 * @param int $price Price in cents
	 */
	public function __construct(
		public string $code,
		public int $price
	)
	{
		ProductValidationHelper::validate_product_code_or_throw($code);
		ProductValidationHelper::validate_price_or_throw($price);
	}
}