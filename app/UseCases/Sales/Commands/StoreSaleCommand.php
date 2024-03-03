<?php

namespace App\UseCases\Sales\Commands;

use App\Domain\Sale\Sale as SaleEntity;
use App\Dtos\SaleDto;
use App\Repositories\Contracts\SaleRepositoryInterface;
use App\UseCases\CommandInterface;

class StoreSaleCommand implements CommandInterface
{
    private SaleEntity $sale;
    private SaleRepositoryInterface $repository;

    public function __construct(
        SaleEntity $sale
    ) {
        $this->sale = $sale;
        $this->repository = app()->make(SaleRepositoryInterface::class);
    }

    public function execute(): SaleDto
    {
        return $this->repository->store($this->sale);
    }
}
