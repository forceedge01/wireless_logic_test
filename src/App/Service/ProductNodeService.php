<?php

declare(strict_types=1);

namespace WirelessLogic\App\Service;

use WirelessLogic\App\Config\Product as ProductConfig;
use WirelessLogic\App\Repository\ProductRepository;
use WirelessLogic\App\Collection\Collection;
use WirelessLogic\App\Entity\Product;

class ProductNodeService
{
    public function __construct(private ProductConfig $config)
    {}

    public function getTitle($node): string
    {
        return $node->filter($this->config->getTitleSelector())->text();
    }

    public function getName($node): string
    {
        return $node->filter($this->config->getNameSelector())->text();
    }

    public function getDescription($node): string
    {
        return $node->filter($this->config->getDescriptionSelector())->text();
    }

    public function getPrice($node): int
    {
        $price = $node->filter($this->config->getPriceSelector())->text();

        return $price = (int) filter_var($price, FILTER_SANITIZE_NUMBER_INT);
    }

    public function getPriceFrequency($node): string
    {
        $priceFrequency = $node->filter($this->config->getPriceFrequencySelector())->text();

        return strpos(strtolower($priceFrequency), 'year') !== false
            ? Product::YEARLY
            : Product::MONTHLY;
    }

    public function getDiscount($node): string
    {
        return $node->filter($this->config->getDiscountSelector())->text('');
    }
}
