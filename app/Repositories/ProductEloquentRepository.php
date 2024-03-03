<?php

namespace App\Repositories;

use App\Domain\Product\Entities\Product;
use App\Dtos\ProductDto;
use App\Mappers\ProductMapper;
use App\Models\Product as ProductModel;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductEloquentRepository implements ProductRepositoryInterface
{
    public function findAll(): array
    {
        return ProductModel::all()->map(fn ($product) => ProductDto::fromEloquent($product))->toArray();
    }

    public function findById(int $id): Product
    {
        $productEloquent = ProductModel::query()->findOrFail($id);
        return ProductMapper::fromEloquent($productEloquent);
    }

    public function store(ProductDto $product): ProductDto
    {
        $productEloquent = ProductMapper::toEloquent($product);
        $productEloquent->save();
        return ProductDto::fromEloquent($productEloquent);
    }
}
