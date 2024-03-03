<?php

namespace App\Repositories;

use App\Domain\Sale\Sale;
use App\Dtos\SaleDto;
use App\Mappers\SaleMapper;
use App\Models\Sale as SaleModel;
use App\Repositories\Contracts\SaleRepositoryInterface;

class SaleEloquentRepository implements SaleRepositoryInterface
{
    public function findAll(): array
    {
        $sales = [];
        foreach (SaleModel::all() as $saleModel) {
            $sales[] = SaleDto::fromEloquent($saleModel);
        }
        return $sales;
    }

    public function findById(int $id): Sale
    {
        $sale = SaleModel::query()->findOrFail($id);
        return SaleMapper::fromEloquent($sale);
    }

    public function store(Sale $sale): SaleDto
    {
        $saleEloquent = SaleMapper::toEloquent($sale);
        $saleEloquent->save();
        foreach ($sale->products as $product) {
            $saleEloquent->products()->attach([$product->product_id => ['amount' => $product->amount]]);
        }
        return SaleDto::fromEloquent($saleEloquent);
    }

    public function update(SaleDto $sale): void
    {
    }

    public function delete(int $sale_id): void
    {
        $sale = SaleModel::query()->findOrFail($sale_id);
        $sale->delete();
    }
}
