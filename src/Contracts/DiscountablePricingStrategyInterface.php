<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Contracts;

interface DiscountablePricingStrategyInterface extends PricingStrategyInterface
{
	/**
	 * Checks if a discounted pricing record exists for given product code.
	 *
	 * @param string $code Product code
	 * @return bool True if discounted price defined for product code, false otherwise
	 */
	function hasDiscountedPricing(string $code): bool;
}