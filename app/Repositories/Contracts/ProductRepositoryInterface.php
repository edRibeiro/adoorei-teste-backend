<?php

namespace App\Repositories\Contracts;

use App\Domain\Product\Entities\Product;
use App\Dtos\ProductDto;

interface ProductRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): Product;
    public function store(ProductDto $product): ProductDto;
}
