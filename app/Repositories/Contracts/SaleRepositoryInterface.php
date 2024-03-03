<?php

namespace App\Repositories\Contracts;

use App\Domain\Sale\Sale;
use App\Dtos\SaleDto;

interface SaleRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): Sale|null;
    public function store(Sale $sale): SaleDto;
    public function update(SaleDto $sale): void;
    public function delete(int $sale_id): void;
}
