<?php

declare(strict_types=1);

namespace WirelessLogic\App\Repository;

use WirelessLogic\App\Config\Product as ProductConfig;
use WirelessLogic\App\Config\ItemInterface;
use WirelessLogic\App\Collection\Collection;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

/**
 * Base class that gives abstract methods for extending classes.
 */
abstract class WebRepository
{
    public function __construct(protected ProductConfig $config, protected Client $client) {}

    public function fetchContainerCrawler(): Crawler
    {
        $crawler = $this->client->request('GET', $this->config->getUrl());

        return $crawler->filter($this->config->getContainerSelector());
    }

    public function build(array $data): Collection
    {
        return new Collection($data);
    }
}
