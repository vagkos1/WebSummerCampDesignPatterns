<?php

declare(strict_types=1);

namespace WebSummerCamp\Tests\WebShop\Coupon;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use WebSummerCamp\WebShop\Coupon\CouponBuilder;
use WebSummerCamp\WebShop\Coupon\LimitedLifetimeCoupon;
use WebSummerCamp\WebShop\Coupon\MinimumPurchaseAmountCoupon;
use WebSummerCamp\WebShop\Coupon\RateCoupon;
use WebSummerCamp\WebShop\Coupon\ValueCoupon;

class CouponBuilderTest extends TestCase
{
    public function testCreateSimpleValueCoupon(): void
    {
        $this->assertEquals(
            new ValueCoupon('COUPON123', new Money(1000, new Currency('EUR'))),
            CouponBuilder::aValueCoupon('COUPON123', 'EUR 1000')->build()
        );
    }

    public function testCreateSimpleRateCoupon(): void
    {
        $this->assertEquals(
            new RateCoupon('COUPON123', 0.25),
            CouponBuilder::aRateCoupon('COUPON123', 0.25)->build()
        );
    }

    public function testCreateComplexRateCoupon(): void
    {
        $expected = new LimitedLifetimeCoupon(
            new MinimumPurchaseAmountCoupon(
                new RateCoupon('COUPON123', .20),
                new Money(7500, new Currency('GBP'))
            ),
            new \DateTimeImmutable('2019-01-01 00:00:00'),
            new \DateTimeImmutable('2019-01-14 23:59:59'),
        );

        $this->assertEquals(
            CouponBuilder::aRateCoupon('COUPON123', .20)
                ->mustRequireMinimumPurchaseAmount('GBP 7500')
                ->mustBeValidBetween('2019-01-01 00:00:00', '2019-01-14 23:59:59')
                ->build(),
            $expected
        );
    }
}
