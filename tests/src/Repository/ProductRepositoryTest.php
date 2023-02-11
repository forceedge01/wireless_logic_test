<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WirelessLogic\App\Config\Product as ProductConfig;
use WirelessLogic\App\Repository\ProductRepository;
use WirelessLogic\App\Entity\Product;
use WirelessLogic\App\Collection\Collection;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

final class ProductRepositoryTest extends TestCase
{
    const URL = 'http:://test';
    const CONTAINER_SELECTOR = 'body .abc';

    public function setUp(): void
    {
        $this->config = $this->createMock(ProductConfig::class);
        $this->client = $this->createMock(Client::class);

        $this
            ->mockConfig('getUrl', self::URL)
            ->mockConfig('getContainerSelector', self::CONTAINER_SELECTOR);

        $this->testObject = new ProductRepository($this->config, $this->client);
    }

    public function testFetchContainerCrawlerNothingReturned()
    {
        $result = $this->testObject->fetchContainerCrawler();

        self::assertInstanceOf(Crawler::class, $result);
    }

    public function testFetchContainerCrawlerSomethingReturned()
    {
        $expected = ['abc'];

        $containerBodyCrawler = $this->createMock(Crawler::class);
        $containerBodyCrawler->expects($this->any())
            ->method('each')
            ->with($this->isType('callable'))
            ->willReturn($expected);

        $crawler = $this->createMock(Crawler::class);
        $crawler->expects($this->once())
            ->method('filter')
            ->with(self::CONTAINER_SELECTOR)
            ->willReturn($containerBodyCrawler);

        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', self::URL)
            ->willReturn($crawler);

        $result = $this->testObject->fetchContainerCrawler();

        self::assertInstanceOf(Crawler::class, $result);
    }

    private function mockConfig($method, $value): self
    {
        $this->config->expects($this->once())
            ->method($method)
            ->willReturn($value);

        return $this;
    }
}
