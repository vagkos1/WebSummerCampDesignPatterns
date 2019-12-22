<?php

declare(strict_types=1);

namespace WebSummerCamp\WebShop\Coupon;

use Money\Money;

class MinimumPurchaseAmountCoupon extends AbstractRestrictedCoupon
{
    private $minimumRequiredAmount;

    public function __construct(CouponInterface $coupon, Money $minimumRequiredAmount)
    {
        parent::__construct($coupon);

        $this->minimumRequiredAmount = $minimumRequiredAmount;
    }

    /**
     * @param Money $totalAmount
     *
     * @return Money
     */
    public function apply(Money $totalAmount): Money
    {
        if ($totalAmount->lessThan($this->minimumRequiredAmount)) {
            return $totalAmount;
        }

        return $this->coupon->apply($totalAmount);
    }
}
