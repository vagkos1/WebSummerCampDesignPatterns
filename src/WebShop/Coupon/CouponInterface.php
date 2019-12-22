<?php

declare(strict_types=1);

namespace WebSummerCamp\WebShop\Coupon;

use Money\Money;

interface CouponInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param Money $totalAmount
     *
     * @return Money
     */
    public function apply(Money $totalAmount): Money;
}
