<?php

declare(strict_types = 1);

namespace WirelessLogic\App\Entity;

/**
 * The data object for a product.
 */
class Product
{
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';

    /**
     * To calculate the yearly cost of the package.
     */
    const YEARLY_MULTIPLIER = 12;

    public function __construct(
        public string $optionTitle,
        public string $name,
        public string $description,
        public int $price,
        public string $priceFrequency,
        public string $discount
    ) {}
}
