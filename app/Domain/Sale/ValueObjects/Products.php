<?php

namespace App\Domain\Sale\ValueObjects;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Sale\ProductSale;
use App\Domain\ValueObjectArray as DomainValueObjectArray;

final class Products extends DomainValueObjectArray
{
    public array $products;

    public function __construct(array $products = [])
    {
        parent::__construct($products);

        foreach ($products as $product) {
            if (!$product instanceof ProductSale) {
                throw new \InvalidArgumentException('Invalid product');
            }
        }
        $this->products = $products;
    }

    public function add(ProductSale $product): void
    {
        $this->products[] = $product;
    }

    public function update(ProductSale $newProduct): void
    {
        $productIds = array_column($this->products, 'product_id');
        if (!in_array($newProduct->product_id, $productIds)) {
            throw new EntityNotFoundException('Product not found');
        }
        $this->offsetSet(array_search($newProduct->product_id, $productIds), $newProduct);
    }

    public function remove(int $product_id): void
    {
        $productIds = array_column($this->products, 'id');
        if (!in_array($product_id, $productIds)) {
            throw new EntityNotFoundException('Product not found');
        }
        $this->offsetUnset(array_search($product_id, $productIds));
    }

    public function jsonSerialize(): array
    {
        return $this->products;
    }

    public function toArray(): array
    {
        return array_map(fn ($product) => $product->toArray(), $this->products);
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}
