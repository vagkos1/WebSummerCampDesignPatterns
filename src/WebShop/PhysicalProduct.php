<?php

declare(strict_types=1);

namespace WebSummerCamp\WebShop;

use Money\Money;
use Ramsey\Uuid\UuidInterface;

class PhysicalProduct implements ProductInterface
{
    private $sku;
    private $unitPrice;
    private $name;

    public function __construct(
        UuidInterface $sku,
        Money $unitPrice,
        string $name
    ) {
        $this->sku       = $sku;
        $this->unitPrice = $unitPrice;
        $this->name      = $name;
    }

    public function getSku(): UuidInterface
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUnitPrice(): Money
    {
        return $this->unitPrice;
    }
}
