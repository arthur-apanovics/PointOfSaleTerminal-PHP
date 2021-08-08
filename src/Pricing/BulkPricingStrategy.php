<?php

declare(strict_types=1);

namespace PointOfSaleTerminal\Pricing;

use InvalidArgumentException;
use PointOfSaleTerminal\Common\ProductValidationHelper;
use PointOfSaleTerminal\Contracts\DiscountablePricingStrategyInterface;
use PointOfSaleTerminal\Models\BulkPrice;
use PointOfSaleTerminal\Models\BulkProduct;
use PointOfSaleTerminal\Models\Product;

class BulkPricingStrategy extends StandardPricingStrategy implements DiscountablePricingStrategyInterface
{
    /**
     * @var Array<int, BulkPrice>
     */
    private array $codeToBulkPriceMap = [];

    /**
     * @param Product[] $pricing
     * @param BulkProduct[] $bulkPricing
     */
    public function __construct(array $pricing, array $bulkPricing)
    {
        parent::__construct($pricing);

        if (ProductValidationHelper::has_duplicates($bulkPricing)) {
            throw new InvalidArgumentException("Bulk pricing list cannot contain duplicate products");
        }

        $this->codeToBulkPriceMap = $this->bulkPricingToDictionary($bulkPricing);
    }

    public function hasDiscountedPricing(string $code): bool
    {
        return isset($this->codeToBulkPriceMap[$code]);
    }

    protected function calculateTotalWithoutGuards(string $code, int $quantity): int
    {
        $result = 0;
        $remaining = $quantity;

        if ($this->hasDiscountedPricing($code)) {
            $bulkPrice = $this->codeToBulkPriceMap[$code];

            while ($remaining >= $bulkPrice->threshold) {
                $result += $bulkPrice->price;
                $remaining -= $bulkPrice->threshold;
            }
        }

        $result += parent::calculateTotalWithoutGuards($code, $remaining);

        return $result;
    }

    /**
     * @param BulkProduct[] $bulkPricing
     * @return Array<string, BulkPrice>
     */
    private function bulkPricingToDictionary(array $bulkPricing): array
    {
        $result = [];
        foreach ($bulkPricing as $bulkProduct){
            $result[$bulkProduct->code] = $bulkProduct->bulkPrice;
        }

        return $result;
    }
}