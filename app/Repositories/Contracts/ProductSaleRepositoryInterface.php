<?php

namespace App\Repositories\Contracts;

use App\Domain\Sale\Sale;

interface ProductSaleRepositoryInterface
{
    public function store(Sale $product, int $saleId): void;
}
