<?php

namespace App\Mappers;

use App\Domain\Sale\Sale;
use App\Domain\Sale\ValueObjects\Amount;
use App\Domain\Sale\ValueObjects\Products;
use App\Dtos\SaleDto;
use App\Models\Sale as SaleModel;
use Illuminate\Http\Request;

class SaleMapper
{
    public static function toEloquent(Sale $sale): SaleModel
    {
        $saleModel = new SaleModel();
        if ($sale->id) {
            $saleModel = SaleModel::query()->findOrFail($sale->id);
        }
        $saleModel->amount = $sale->amount->amount;
        return $saleModel;
    }

    public static function fromEloquent(SaleModel $sale): Sale
    {
        $sale->load('products');
        $products = new Products([]);
        $saleEntity = new Sale($sale->id, new Amount($sale->amount), $products);
        foreach ($sale->products as $product) {
            $saleEntity->addProduct(ProductSaleMapper::fromEloquent($product, $product->pivot->amount));
        }
        return $saleEntity;
    }

    public static function fromRequest(Request $request, ?int $sale_id = null): Sale
    {
        $products = new Products(
            array_map(function ($product) {
                return ProductSaleMapper::fromArray($product);
            }, SaleDto::verifyProductsFromRequest($request))
        );
        $amount = array_reduce($products->getProducts(), fn ($total, $product) => ($total + $product->getTotal()), 0);
        return new Sale($sale_id, new Amount($amount), $products);
    }
}
