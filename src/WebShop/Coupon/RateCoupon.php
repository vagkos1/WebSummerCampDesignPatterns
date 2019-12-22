<?php

declare(strict_types=1);

namespace WebSummerCamp\WebShop\Coupon;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Money\Money;

class RateCoupon implements CouponInterface
{
    /** @var string */
    private $code;

    /** @var float */
    private $discountRate;

    /**
     * @param string $code
     * @param float $rate
     *
     * @throws AssertionFailedException
     */
    public function __construct(string $code, float $rate)
    {
        Assertion::between($rate, 0, 1);
        $this->code         = $code;
        $this->discountRate = $rate;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param Money $totalAmount
     *
     * @return Money
     */
    public function apply(Money $totalAmount): Money
    {
        return $totalAmount->subtract($totalAmount->multiply($this->discountRate));
    }
}
