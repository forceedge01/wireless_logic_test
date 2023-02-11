<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WirelessLogic\App\Config\Product as ProductConfig;
use WirelessLogic\App\Repository\ProductRepository;
use WirelessLogic\App\Entity\Product;
use WirelessLogic\App\Service\ProductNodeService;
use WirelessLogic\App\Collection\Collection;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

final class ProductNodeServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->config = $this->createMock(ProductConfig::class);

        $this->testObject = new ProductNodeService($this->config);
    }

    public function testGetTitle()
    {
        $selector = 'div .title';
        $expected = 'my title';

        $this->config->expects($this->once())
            ->method('getTitleSelector')
            ->willReturn($selector);

        $node = $this->createMock(Crawler::class);
        $this->mockCrawlerNode($node, $selector, $expected);

        $result = $this->testObject->getTitle($node);

        self::assertSame($expected, $result);
    }

    public function testGetName()
    {
        $selector = 'div .name';
        $expected = 'my name';

        $this->config->expects($this->once())
            ->method('getNameSelector')
            ->willReturn($selector);

        $node = $this->createMock(Crawler::class);
        $this->mockCrawlerNode($node, $selector, $expected);

        $result = $this->testObject->getName($node);

        self::assertSame($expected, $result);
    }

    public function testGetDescription()
    {
        $selector = 'div .description';
        $expected = 'my description';

        $this->config->expects($this->once())
            ->method('getDescriptionSelector')
            ->willReturn($selector);

        $node = $this->createMock(Crawler::class);
        $this->mockCrawlerNode($node, $selector, $expected);

        $result = $this->testObject->getDescription($node);

        self::assertSame($expected, $result);
    }

    public function testGetPrice()
    {
        $selector = 'div .price';
        $input = '$59.99';
        $expected = 5999;

        $this->config->expects($this->once())
            ->method('getPriceSelector')
            ->willReturn($selector);

        $node = $this->createMock(Crawler::class);
        $this->mockCrawlerNode($node, $selector, $input);

        $result = $this->testObject->getPrice($node);

        self::assertSame($expected, $result);
    }

    public function testGetPriceFrequencyMonthly()
    {
        $selector = 'div .price_frequency';
        $expected = Product::MONTHLY;
        $input = 'per month';

        $this->config->expects($this->once())
            ->method('getPriceFrequencySelector')
            ->willReturn($selector);

        $node = $this->createMock(Crawler::class);
        $this->mockCrawlerNode($node, $selector, $input);

        $result = $this->testObject->getPriceFrequency($node);

        self::assertSame($expected, $result);
    }

    public function testGetPriceFrequencyYearly()
    {
        $selector = 'div .price_frequency';
        $expected = Product::YEARLY;
        $input = 'per year';

        $this->config->expects($this->once())
            ->method('getPriceFrequencySelector')
            ->willReturn($selector);

        $node = $this->createMock(Crawler::class);
        $this->mockCrawlerNode($node, $selector, $input);

        $result = $this->testObject->getPriceFrequency($node);

        self::assertSame($expected, $result);
    }

    public function testGetDiscount()
    {
        $selector = 'div .discount';
        $expected = '$10 off';

        $this->config->expects($this->once())
            ->method('getDiscountSelector')
            ->willReturn($selector);

        $node = $this->createMock(Crawler::class);
        $this->mockCrawlerNode($node, $selector, $expected);

        $result = $this->testObject->getDiscount($node);

        self::assertSame($expected, $result);
    }

    private function mockCrawlerNode(Crawler $textCrawler, string $with, string $text): self
    {
        $textCrawler->expects($this->any())
            ->method('filter')
            ->with($with)
            ->willReturnSelf();
        $textCrawler->expects($this->any())
            ->method('text')
            ->willReturn($text);

        return $this;
    }
}
