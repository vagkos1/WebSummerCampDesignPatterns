<?php

declare(strict_types=1);

namespace WebSummerCamp\Tests\WebShop\Coupon;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use WebSummerCamp\WebShop\Coupon\LimitedLifetimeCoupon;
use WebSummerCamp\WebShop\Coupon\MinimumPurchaseAmountCoupon;
use WebSummerCamp\WebShop\Coupon\RateCoupon;
use WebSummerCamp\WebShop\Coupon\ValueCoupon;

class LimitedLifetimeCouponTest extends TestCase
{
    public function testCouponIsEligible(): void
    {
        // decorate the ValueCoupon
        $coupon = new LimitedLifetimeCoupon(
            new ValueCoupon('COUPON123', new Money(2000, new Currency('GBP'))),
            (new \DateTimeImmutable('now'))->modify('-5 days'), // coupon applies to "now"
            (new \DateTimeImmutable('now'))->modify('+5 days'),
        );

        $this->assertEquals(
            new Money(9000, new Currency('GBP')),
            $coupon->apply(
                new Money(11000, new Currency('GBP'))
            )
        );
    }

    public function testCouponIsNotEligible(): void
    {
        $coupon = new LimitedLifetimeCoupon(
            new ValueCoupon('COUPON123', new Money(2000, new Currency('GBP'))),
            (new \DateTimeImmutable('now'))->modify('+5 days'), // valid from is in the future :)
            (new \DateTimeImmutable('now'))->modify('+15 days'),
        );

        $this->assertEquals(
            new Money(9000, new Currency('GBP')),
            $coupon->apply(
                new Money(9000, new Currency('GBP'))
            )
        );
    }

    public function testComplexCouponCombination(): void
    {
        // RateCoupon is decorated by the 2 concrete restriction coupon decorators.
        // here the order by which the decorators are executed is not important.
        $coupon = new LimitedLifetimeCoupon(
            new MinimumPurchaseAmountCoupon(
                new RateCoupon('COUPON123', .20),
                new Money(7500, new Currency('GBP'))
            ),
            (new \DateTimeImmutable('now'))->modify('-5 days'), // coupon applies to "now"
            (new \DateTimeImmutable('now'))->modify('+5 days'),
        );

        $this->assertEquals(
            new Money(8000, new Currency('GBP')),
            $coupon->apply(
                new Money(10000, new Currency('GBP'))
            )
        );
    }
}
