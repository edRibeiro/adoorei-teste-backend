<?php

namespace App\UseCases\Sales\Queries;

use App\Repositories\Contracts\SaleRepositoryInterface;
use App\UseCases\QueryInterface;

class FindSaleByIdQuery implements QueryInterface
{
    private SaleRepositoryInterface $repository;
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->repository = app()->make(SaleRepositoryInterface::class);
    }

    public function handle(): mixed
    {
        return $this->repository->findById($this->id);
    }
}
