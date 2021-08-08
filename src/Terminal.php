<?php

declare(strict_types=1);

namespace PointOfSaleTerminal;

use Exception;
use InvalidArgumentException;
use PointOfSaleTerminal\Contracts\PointOfSaleTerminalInterface;
use PointOfSaleTerminal\Contracts\PricingStrategyInterface;

class Terminal implements PointOfSaleTerminalInterface
{
    private ?PricingStrategyInterface $pricingStrategy = null;
    private array $scannedProducts = [];

    public function setPricing(PricingStrategyInterface $pricingStrategy): void
    {
        $this->pricingStrategy = $pricingStrategy;
    }

    public function scanProduct(string $code): void
    {
        $this->checkPricingStrategySetOrThrow();

        if (!$this->pricingStrategy->hasPricing($code)) {
            throw new InvalidArgumentException("Product with code '$code' not found in pricing list");
        }

        $this->scannedProducts[] = $code;
    }

    public function calculateTotal(): int
    {
        $this->checkPricingStrategySetOrThrow();

        return $this->pricingStrategy->calculateTotalForCodes(
            $this->scannedProducts
        );
    }

    private function checkPricingStrategySetOrThrow()
    {
        if ($this->pricingStrategy === null) {
            throw new Exception('No pricing strategy set. Use "setPricing" to set one.');
        }
    }
}