<?php

namespace App\UseCases\Sales\Queries;

use App\Repositories\Contracts\SaleRepositoryInterface;
use App\UseCases\QueryInterface;

class FindAllSalesQuery implements QueryInterface
{
    private SaleRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app()->make(SaleRepositoryInterface::class);
    }

    public function handle(): mixed
    {
        return $this->repository->findAll();
    }
}
