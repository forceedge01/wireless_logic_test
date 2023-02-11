<?php

declare(strict_types=1);

namespace WirelessLogic\App\Service;

use WirelessLogic\App\Repository\ProductRepository;
use WirelessLogic\App\Collection\Collection;
use WirelessLogic\App\Entity\Product;

class ProductService
{
    const ORDER_DESC = 'desc';
    const ORDER_ASC = 'asc';

    public function __construct(private ProductRepository $repository, private ProductNodeService $nodeService)
    {}

    public function fetchData(): Collection
    {
        $crawler = $this->repository->fetchContainerCrawler();
        $nodeService = $this->nodeService;

        $products = $crawler->each(function ($node) use ($nodeService) {
            return $this->getProduct($node);
        });

        return $this->repository->build($products);
    }

    public function getProduct($node): Product
    {
        $title = $this->nodeService->getTitle($node);
        $name = $this->nodeService->getName($node);
        $description = $this->nodeService->getDescription($node);
        $price = $this->nodeService->getPrice($node);
        $priceFrequency = $this->nodeService->getPriceFrequency($node);
        $discount = $this->nodeService->getDiscount($node);

        return new Product(
            $title,
            $name,
            $description,
            $price,
            $priceFrequency,
            $discount
        );
    }

    public function sortByYearlyPrice(Collection $collection, string $order): Collection
    {
        $data = $collection->getData();

        usort($data, function ($item1, $item2) use ($order) {
            $yearlyCost1 = $this->getYearlyCost($item1);
            $yearlyCost2 = $this->getYearlyCost($item2);

            return (int) match ($order) {
                'asc' => $yearlyCost1 > $yearlyCost2,
                'desc' => $yearlyCost1 < $yearlyCost2
            };
        });

        $collection->setData($data);

        return $collection;
    }

    public function generateJsonOutput(Collection $collection): string
    {
        $output = [];

        foreach ($collection->getData() as $product) {
            $output[] = [
                'optionTitle' => $product->optionTitle,
                'description' => $product->description,
                'price' => $product->price,
                'discount' => $product->discount
            ];
        }

        return json_encode($output);
    }

    private function getYearlyCost(Product $product): int
    {
        return $product->priceFrequency == Product::YEARLY
            ? $product->price
            : $product->price * Product::YEARLY_MULTIPLIER;
    }
}
