<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WirelessLogic\App\Config\Product as ProductConfig;
use WirelessLogic\App\Repository\ProductRepository;
use WirelessLogic\App\Entity\Product;
use WirelessLogic\App\Service\ProductService;
use WirelessLogic\App\Service\ProductNodeService;
use WirelessLogic\App\Collection\Collection;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

final class ProductServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->productNodeService = $this->createMock(ProductNodeService::class);

        $this->testObject = new ProductService(
            $this->productRepository,
            $this->productNodeService
        );
    }

    public function testFetchData()
    {
        $expected = $this->createMock(Collection::class);

        $crawler = $this->createMock(Crawler::class);
        $crawler->expects($this->once())
            ->method('each');

        $this->productRepository->expects($this->once())
            ->method('fetchContainerCrawler')
            ->willReturn($crawler);
        $this->productRepository->expects($this->once())
            ->method('build')
            ->willReturn($expected);

        $result = $this->testObject->fetchData();

        self::assertSame($expected, $result);
    }

    public function testGetProduct()
    {
        $node = $this->createMock(Crawler::class);

        $this->productNodeService->expects($this->once())
            ->method('getTitle')
            ->with($node)
            ->willReturn('title');
        $this->productNodeService->expects($this->once())
            ->method('getName')
            ->with($node)
            ->willReturn('name');
        $this->productNodeService->expects($this->once())
            ->method('getDescription')
            ->with($node)
            ->willReturn('description');
        $this->productNodeService->expects($this->once())
            ->method('getPrice')
            ->with($node)
            ->willReturn(399);
        $this->productNodeService->expects($this->once())
            ->method('getPriceFrequency')
            ->with($node)
            ->willReturn('price frequency');
        $this->productNodeService->expects($this->once())
            ->method('getDiscount')
            ->with($node)
            ->willReturn('discount');

        $result = $this->testObject->getProduct($node);

        self::assertInstanceOf(Product::class, $result);
        self::assertSame('title', $result->optionTitle);
        self::assertSame('name', $result->name);
        self::assertSame('description', $result->description);
        self::assertSame(399, $result->price);
        self::assertSame('price frequency', $result->priceFrequency);
        self::assertSame('discount', $result->discount);
    }

    public function testSortByYearlyPrice()
    {
        $product1 = $this->createMock(Product::class);
        $product1->price = 500;
        $product1->priceFrequency = PRODUCT::YEARLY;

        $product2 = $this->createMock(Product::class);
        $product2->price = 600;
        $product2->priceFrequency = PRODUCT::YEARLY;

        $product3 = $this->createMock(Product::class);
        $product3->price = 300;
        $product3->priceFrequency = PRODUCT::YEARLY;

        // 30 * 12 = 360
        $product4 = $this->createMock(Product::class);
        $product4->price = 30;
        $product4->priceFrequency = PRODUCT::MONTHLY;

        // 60 * 12 = 720
        $product5 = $this->createMock(Product::class);
        $product5->price = 60;
        $product5->priceFrequency = PRODUCT::MONTHLY;

        $collection = $this->createMock(Collection::class);
        $collection->expects($this->once())
            ->method('getData')
            ->willReturn([
                $product1,
                $product2,
                $product3,
                $product4,
                $product5,
            ]);

        $collection->expects($this->once())
            ->method('setData')
            ->with([
                $product5,
                $product2,
                $product1,
                $product4,
                $product3,
            ]);

        $order = ProductService::ORDER_DESC;

        $result = $this->testObject->sortByYearlyPrice($collection, $order);

        self::assertInstanceOf(Collection::class, $result);
    }

    public function testGenerateJsonOutputEmptyCollection()
    {
        $collection = $this->createMock(Collection::class);

        $result = $this->testObject->generateJsonOutput($collection);

        self::assertEquals('[]', $result);
    }

    public function testGenerateJsonOutputItemsCollection()
    {
        $product1 = $this->createMock(Product::class);
        $product1->price = 500;
        $product1->optionTitle = 'option 1';
        $product1->priceFrequency = PRODUCT::YEARLY;
        $product1->description = 'description 1';
        $product1->discount = 'discount 1';

        $product2 = $this->createMock(Product::class);
        $product2->price = 600;
        $product2->optionTitle = 'option 2';
        $product2->priceFrequency = PRODUCT::YEARLY;
        $product2->description = 'description 2';
        $product2->discount = 'discount 2';

        $product3 = $this->createMock(Product::class);
        $product3->price = 60;
        $product3->optionTitle = 'option 3';
        $product3->priceFrequency = PRODUCT::MONTHLY;
        $product3->description = 'description 3';
        $product3->discount = 'discount 3';

        $collection = $this->createMock(Collection::class);
        $collection->expects($this->once())
            ->method('getData')
            ->willReturn([
                $product1,
                $product2,
                $product3
            ]);

        $result = $this->testObject->generateJsonOutput($collection);

        $collection = json_decode($result);

        self::assertEquals('[{"optionTitle":"option 1","description":"description 1","price":500,"discount":"discount 1"},{"optionTitle":"option 2","description":"description 2","price":600,"discount":"discount 2"},{"optionTitle":"option 3","description":"description 3","price":60,"discount":"discount 3"}]', $result);
        self::assertStringNotContainsString('priceFrequency', $result);
        self::assertTrue(json_last_error() === JSON_ERROR_NONE);
    }
}
