<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use PointOfSaleTerminal\Models\BulkPrice;
use PointOfSaleTerminal\Models\BulkProduct;
use PointOfSaleTerminal\Models\Product;
use PointOfSaleTerminal\Pricing\BulkPricingStrategy;
use PointOfSaleTerminal\Terminal;

class PointOfSaleTerminalTest extends TestCase
{
    public function codeSequenceProvider(): array
    {
        return [
            "A,B,C,D,A,B,A" => [["A", "B", "C", "D", "A", "B", "A",], 1325],
            "C,C,C,C,C,C,C" => [["C", "C", "C", "C", "C", "C", "C",], 600],
            "A,B,C,D" => [["A", "B", "C", "D",], 725],
            "B,B,B,B,B" => [["B", "B", "B", "B", "B",], 2125],
            "B,B,B,D,D,D" => [["B", "B", "B", "D", "D", "D",], 1500],
            "D" => [["D",], 75],
        ];
    }

    /**
     * @dataProvider codeSequenceProvider
     * @covers Terminal::calculateTotal()
     *
     * @param string[] $codes
     * @param int $expected
     */
    public function test_calculate_total_with_bulk_discount_calculates_total(array $codes, int $expected)
    {
        $pricing = [
            new Product("A", 125),
            new Product("B", 425),
            new Product("C", 100),
            new Product("D", 75),
        ];
        $bulkPricing = [
            new BulkProduct("A", new BulkPrice(3, 300)),
            new BulkProduct("C", new BulkPrice(6, 500)),
        ];
        $sut = new Terminal();
        $sut->setPricing(new BulkPricingStrategy($pricing, $bulkPricing));

        foreach ($codes as $code) {
            $sut->scanProduct($code);
        }

        $this->assertEquals($expected, $sut->calculateTotal());
    }
}