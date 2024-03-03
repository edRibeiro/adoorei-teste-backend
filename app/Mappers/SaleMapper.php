<?php

namespace App\Mappers;

use App\Domain\Exceptions\MinimumValueException;
use App\Domain\Exceptions\RequiredException;
use App\Domain\Sale\Sale;
use App\Domain\Sale\ValueObjects\Amount;
use App\Domain\Sale\ValueObjects\Products;
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
            logger($product);
            // $products[] = ProductSaleMapper::fromEloquent($product, $product->pivot->amount);
            $saleEntity->addProduct(ProductSaleMapper::fromEloquent($product, $product->pivot->amount));
        }
        logger('SALE', [$saleEntity]);
        return $saleEntity;
    }

    public static function fromRequest(Request $request, ?int $sale_id = null): Sale
    {
        $productsRequest = $request->input('products');
        if (!$productsRequest) {
            throw new RequiredException('products');
        }
        if (count($productsRequest) < 1) {
            throw new MinimumValueException('products', '1');
        }

        return new Sale($sale_id, new Amount($request->input('amount')), new Products(
            array_map(function ($product) {
                return ProductSaleMapper::fromArray($product);
            }, $productsRequest)
        ));
    }
}
