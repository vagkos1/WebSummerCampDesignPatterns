<?php

declare(strict_types=1);

namespace WebSummerCamp\WebShop\Coupon;

/**
 * Abstract Decorator
 */
abstract class AbstractRestrictedCoupon implements CouponInterface
{
    protected $coupon;

    public function __construct(CouponInterface $coupon)
    {
        $this->coupon = $coupon;
    }

    public function getCode(): string
    {
        return $this->coupon->getCode();
    }
}
