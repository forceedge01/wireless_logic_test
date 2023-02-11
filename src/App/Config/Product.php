<?php

declare(strict_types = 1);

namespace WirelessLogic\App\Config;

use UnexpectedValueException;

/**
 * Configuration for where to fetch the data from.
 */
class Product implements ItemInterface
{
    public function __construct(
        private string $url,
        private string $containerSelector,
        private string $titleSelector,
        private string $nameSelector,
        private string $descriptionSelector,
        private string $priceSelector,
        private string $priceFrequencySelector,
        private string $discountSelector
    ) {
        if (! $url) {
            throw new UnexpectedValueException('Scrape Url must be defined in the parameters.yml file.');
        }
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getContainerSelector(): string
    {
        return $this->containerSelector;
    }

    public function getTitleSelector(): string
    {
        return $this->titleSelector;
    }

    public function getDescriptionSelector(): string
    {
        return $this->descriptionSelector;
    }

    public function getPriceSelector(): string
    {
        return $this->priceSelector;
    }

    public function getDiscountSelector(): string
    {
        return $this->discountSelector;
    }

    public function getPriceFrequencySelector(): string
    {
        return $this->priceFrequencySelector;
    }

    public function getNameSelector(): string
    {
        return $this->nameSelector;
    }
}
