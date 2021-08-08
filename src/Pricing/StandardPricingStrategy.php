<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Pricing;

use InvalidArgumentException;
use PointOfSaleTerminal\Common\ProductValidationHelper;
use PointOfSaleTerminal\Contracts\PricingStrategyInterface;
use PointOfSaleTerminal\Models\Product;

class StandardPricingStrategy implements PricingStrategyInterface
{
    /**
     * @var Array<string, int>
     */
    private array $codeToPriceMap = [];

    /**
     * @param Product[] $pricing
     */
    public function __construct(array $pricing)
    {
        if (empty($pricing)) {
            throw new InvalidArgumentException("Pricing cannot be empty");
        }
        if (ProductValidationHelper::has_duplicates($pricing)) {
            throw new InvalidArgumentException("Pricing cannot contain duplicate product codes");
        }

        $this->codeToPriceMap = $this->productsToDictionary($pricing);
    }

    public function hasPricing(string $code): bool
    {
        return array_key_exists($code, $this->codeToPriceMap);
    }

    public function getPrice(string $code): int
    {
        if (!$this->hasPricing($code)) {
            throw new InvalidArgumentException("No price found for product with code '{$code}'");
        }

        return $this->codeToPriceMap[$code];
    }

    public function calculateTotal(string $code, int $quantity): int
    {
        if ($quantity == 0) {
            return 0;
        }
        if ($quantity < 0) {
            throw new InvalidArgumentException('Cannot calculate total for negative quantities');
        }

        return $this->calculateTotalWithoutGuards($code, $quantity);
    }

    public function calculateTotalForCodes(array $codes): int
    {
        if (empty($codes)) {
            return 0;
        }

        $result = 0;
        $codeToQuantity = $this->groupByCode($codes);

        foreach ($codeToQuantity as $code => $quantity) {
            $result += $this->calculateTotal($code, $quantity);
        }

        return $result;
    }

    protected function calculateTotalWithoutGuards(string $code, int $quantity): int
    {
        return $this->getPrice($code) * $quantity;
    }

    private function groupByCode(array $codes): array
    {
        $result = [];
        foreach ($codes as $code) {
            if (!array_key_exists($code, $result)) {
                $result[$code] = 0;
            }

            $result[$code] = $result[$code] + 1;
        }

        return $result;
    }

    /**
     * @param Product[] $products
     * @return Array<string, int>
     */
    private function productsToDictionary(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            $result[$product->code] = $product->price;
        }

        return $result;
    }
}