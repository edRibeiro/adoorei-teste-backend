<?php

namespace App\Repositories;

use App\Domain\Sale\Sale;

use App\Repositories\Contracts\ProductSaleRepositoryInterface;

class ProductSaleEloquentRepository implements ProductSaleRepositoryInterface
{
    public function store(Sale $sale, int $saleId): void
    {
    }
}
