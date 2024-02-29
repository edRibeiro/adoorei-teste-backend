<?php

namespace App\UseCases\Products\Commands;

use App\Dtos\ProductDto;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\UseCases\CommandInterface;

class StoreProductCommand implements CommandInterface
{
    private ProductDto $product;
    private ProductRepositoryInterface $repository;

    public function __construct(
        ProductDto $product
    ) {
        $this->product = $product;
        $this->repository = app()->make(ProductRepositoryInterface::class);
    }

    public function execute(): ProductDto
    {
        return $this->repository->store($this->product);
    }
}
