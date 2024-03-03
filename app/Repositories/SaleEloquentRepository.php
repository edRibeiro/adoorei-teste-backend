<?php

namespace App\Repositories;

use App\Domain\Sale\Sale;
use App\Dtos\SaleDto;
use App\Mappers\SaleMapper;
use App\Models\Sale as SaleModel;
use App\Repositories\Contracts\SaleRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($sale) {
            $saleEloquent = SaleMapper::toEloquent($sale);
            $saleEloquent->save();
            foreach ($sale->products as $product) {
                $saleEloquent->products()->attach([$product->product_id => ['amount' => $product->amount]]);
            }
            return SaleDto::fromEloquent($saleEloquent);
        });
    }

    public function update(SaleDto $sale): void
    {
        $saleEloquent = SaleModel::query()->findOrFail($sale->sale_id);
        $saleEloquent->products()->detach();
        foreach ($sale->products as $product) {
            $saleEloquent->products()->attach([$product->product_id => ['amount' => $product->amount]]);
        }
        $saleEloquent->load('products');
        $saleEloquent->products->reduce(fn ($total, $product) => ($total + ($product->price * $product->pivot->amount)));
        /* $saleEloquent->amount = array_reduce(
            $saleEloquent->products,
            fn ($total, $product) => ($total + ($product->price * $product->pivot->amount)),
            0.0
        ); */
        $saleEloquent->save();
    }

    public function delete(int $sale_id): void
    {
        $sale = SaleModel::query()->findOrFail($sale_id);
        $sale->delete();
    }
}
