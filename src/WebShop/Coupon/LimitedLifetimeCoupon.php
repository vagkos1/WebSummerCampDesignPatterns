<?php

declare(strict_types=1);

namespace WebSummerCamp\WebShop\Coupon;

use Money\Money;

/**
 * Concrete Decorator
 */
class LimitedLifetimeCoupon extends AbstractRestrictedCoupon
{
    /** @var \DateTimeImmutable */
    private $validFrom;

    /** @var \DateTimeImmutable */
    private $validUntil;

    /**
     * @param CouponInterface    $coupon
     * @param \DateTimeImmutable $validFrom
     * @param \DateTimeImmutable $validUntil
     */
    public function __construct(
        CouponInterface $coupon,
        \DateTimeImmutable $validFrom,
        \DateTimeImmutable $validUntil
    ) {
        parent::__construct($coupon);

        // these two objects can be encapsulated in a DateRange object that also checks
        // that $validFrom comes before $validUntil
        $this->validFrom  = $validFrom;
        $this->validUntil = $validUntil;
    }

    /**
     * @param Money $totalAmount
     * @return Money
     *
     * @throws \Exception
     */
    public function apply(Money $totalAmount): Money
    {
        $now = new \DateTimeImmutable('now');
        if ($now < $this->validFrom || $now > $this->validUntil) {
            return $totalAmount;
            // could also throw a CouponException here but then it would be difficult to know
            // what happened if different discounts did no get applied, we'd only get the first exception
        }

        return $this->coupon->apply($totalAmount);
    }
}
