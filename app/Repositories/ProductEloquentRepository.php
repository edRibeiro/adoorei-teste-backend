<?php

namespace App\Repositories;

use App\Dtos\ProductDto;
use App\Mappers\ProductMapper;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductEloquentRepository implements ProductRepositoryInterface
{
    public function findAll(): array
    {
        return Product::all()->map(fn ($product) => ProductDto::fromEloquent($product))->toArray();
    }
    public function store(ProductDto $product): ProductDto
    {
        $productEloquent = ProductMapper::toEloquent($product);
        $productEloquent->save();
        return ProductDto::fromEloquent($productEloquent);
    }
}
