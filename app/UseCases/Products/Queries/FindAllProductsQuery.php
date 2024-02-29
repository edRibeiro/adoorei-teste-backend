<?php

namespace App\UseCases\Products\Queries;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\UseCases\QueryInterface;

class FindAllProductsQuery implements QueryInterface
{
    private ProductRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app()->make(ProductRepositoryInterface::class);
    }

    public function handle(): mixed
    {
        return $this->repository->findAll();
    }
}
