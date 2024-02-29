<?php

namespace App\Repositories\Contracts;

use App\Dtos\ProductDto;

interface ProductRepositoryInterface
{
    public function findAll(): array;
    public function store(ProductDto $product): ProductDto;
}
