<?php

declare(strict_types=1);

namespace WebSummerCamp\Tests\WebShop;

use Assert\AssertionFailedException;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use WebSummerCamp\WebShop\ComboProduct;
use WebSummerCamp\WebShop\PhysicalProduct;

class ComboProductTest extends TestCase
{
    public function testComplexComboProductWithoutCustomPrice(): void
    {
        $products = [
            new PhysicalProduct(
                Uuid::uuid4(),
                new Money(12000, new Currency('GBP')),
                'WebSummerCamp'
            ),
            new ComboProduct(Uuid::uuid4(), 'NestedCombo', [
                new PhysicalProduct(
                    Uuid::uuid4(),
                    new Money(9000, new Currency('GBP')),
                    'WebSummerCamp'
                ),
                new PhysicalProduct(
                    Uuid::uuid4(),
                    new Money(8000, new Currency('GBP')),
                    'WebSummerCamp'
                )
            ]),
        ];

        $combo = new ComboProduct(
            Uuid::uuid4(),
            'Test',
            $products
        );

        $this->assertEquals(
            new Money(29000, new Currency('GBP')),
            $combo->getUnitPrice()
        );
    }

    public function testComboProductWithCustomPrice(): void
    {
        $products = [
            new PhysicalProduct(
                Uuid::uuid4(),
                new Money(12000, new Currency('GBP')),
                'WebSummerCamp'
            ),
            new PhysicalProduct(
                Uuid::uuid4(),
                new Money(9000, new Currency('GBP')),
                'WebSummerCamp'
            )
        ];

        $combo = new ComboProduct(
            Uuid::uuid4(),
            'Test',
            $products,
            new Money(14500, new Currency('GBP'))
        );

        $this->assertEquals(
            new Money(14500, new Currency('GBP')),
            $combo->getUnitPrice()
        );
    }

    public function testInvalidComboProduct(): void
    {
        $this->expectException(AssertionFailedException::class);

        new ComboProduct(Uuid::uuid4(), 'Test', [
            new PhysicalProduct(
                Uuid::uuid4(),
                new Money(12000, new Currency('GBP')),
                'WebSummerCamp'
            )
        ]);
    }

    public function testSinglePhysicalProduct(): void
    {
        $product = new PhysicalProduct(
            Uuid::fromString('0000021b-b867-4ce7-ae83-51ab254d55da'),
            new Money(12000, new Currency('GBP')),
            'WebSummerCamp'
        );

        $this->assertEquals(
            Uuid::fromString('0000021b-b867-4ce7-ae83-51ab254d55da'),
            $product->getSku()
        );

        $this->assertEquals(new Money(12000, new Currency('GBP')), $product->getUnitPrice());
        $this->assertSame('WebSummerCamp', $product->getName());
    }
}
