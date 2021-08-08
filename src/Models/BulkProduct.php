<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Models;

use PointOfSaleTerminal\Common\ProductValidationHelper;

/**
 * Represents a map from a product code to a bulk price definition.
 */
class BulkProduct
{
	/**
	 * @param string $code Product code
	 * @param BulkPrice $bulkPrice Bulk price entry
	 */
	public function __construct(
		public string $code,
		public BulkPrice $bulkPrice
	)
	{
		ProductValidationHelper::validate_product_code_or_throw($code);
	}
}