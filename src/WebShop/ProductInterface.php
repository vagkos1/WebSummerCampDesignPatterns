<?php

declare(strict_types=1);

namespace WebSummerCamp\WebShop;

use Money\Money;
use Ramsey\Uuid\UuidInterface;

interface ProductInterface
{
    public function getSku(): UuidInterface;

    public function getName(): string;

    public function getUnitPrice(): Money;

//    public function getWeight(): Weight;
}
