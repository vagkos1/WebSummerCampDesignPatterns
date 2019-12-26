<?php

declare(strict_types=1);

namespace WebSummerCamp\WebShop\Coupon;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Money\Currency;
use Money\Money;

class CouponBuilder
{
    /** @var CouponInterface */
    private $coupon;

    /**
     * @param string $code
     * @param string $value
     *
     * @return CouponBuilder
     *
     * @throws AssertionFailedException
     */
    public static function aValueCoupon(string $code, string $value): self
    {

        return new static(new ValueCoupon($code, static::parseMoney($value)));
    }

    /**
     * @param string $code
     * @param float $rate
     *
     * @return CouponBuilder
     *
     * @throws AssertionFailedException
     */
    public static function aRateCoupon(string $code, float $rate): self
    {
        return new static(new RateCoupon($code, $rate));
    }

    /**
     * @param CouponInterface $coupon
     */
    private function __construct(CouponInterface $coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * @param string $value
     *
     * @return CouponBuilder
     *
     * @throws AssertionFailedException
     */
    public function mustRequireMinimumPurchaseAmount(string $value): self
    {
        $this->coupon = new MinimumPurchaseAmountCoupon($this->coupon, static::parseMoney($value));

        return $this;
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return CouponBuilder
     *
     * @throws \Exception
     */
    public function mustBeValidBetween(string $from, string $to): self
    {
        $this->coupon = new LimitedLifetimeCoupon(
            $this->coupon,
            new \DateTimeImmutable($from),
            new \DateTimeImmutable($to),
        );

        return $this;
    }

    /**
     * @return CouponInterface
     */
    public function build(): CouponInterface
    {
        return $this->coupon;
    }

    /**
     * Parsing can be extracted to another object
     *
     * @param string $value
     *
     * @return Money
     *
     * @throws AssertionFailedException
     */
    private static function parseMoney(string $value): Money
    {
        Assertion::regex($value, '/[A-Z]{3} \d+$/');

        [$currencyCode, $amount] = explode(' ', $value);

        return new Money($amount, new Currency($currencyCode));
    }
}
