<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Contracts;

interface PricingStrategyInterface
{
	/**
	 * Checks if a pricing record exists for given product code.
	 *
	 * @param string $code Product code
	 * @return bool True if price defined for product code, false otherwise
	 */
	function hasPricing(string $code): bool;

	/**
	 * Retrieves price for given product code.
	 *
	 * @param string $code Product code
	 * @return int Price
	 */
	function getPrice(string $code): int;

	/**
	 * Calculates total price for a single product code based on quantity.
	 *
	 * @param string $code Product code
	 * @param int $quantity How many instances to calculate for
	 * @return int Total price
	 */
	function calculateTotal(string $code, int $quantity): int;

	/**
	 * Calculates total price for a mixed set of codes.
	 *
	 * @param Array<string, int> $codes Product codes in any order and quantity
	 * @return int Total price
	 */
	function calculateTotalForCodes(array $codes): int;
}