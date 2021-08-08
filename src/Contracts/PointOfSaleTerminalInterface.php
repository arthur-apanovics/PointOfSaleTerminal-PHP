<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Contracts;

use InvalidArgumentException;

interface PointOfSaleTerminalInterface
{
	/**
	 * Sets internal pricing strategy that will be used for calculating totals.
	 *
	 * @param PricingStrategyInterface $pricingStrategy Valid pricing strategy
	 */
	function setPricing(PricingStrategyInterface $pricingStrategy): void;

	/**
	 * Stores product code in local state.
	 *
	 * @param string $code Product code
	 * @throws InvalidArgumentException If pricing is not found for product code
	 */
	function scanProduct(string $code): void;

	/**
	 * Calculates total price for all previously scanned products.
	 *
	 * @return int Total price
	 */
	function calculateTotal(): int;
}