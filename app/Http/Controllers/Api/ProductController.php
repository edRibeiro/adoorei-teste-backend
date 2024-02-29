<?php

namespace App\Http\Controllers\Api;

use App\Dtos\ProductDto;
use App\Http\Controllers\Controller;
use App\UseCases\Products\Commands\StoreProductCommand;
use App\UseCases\Products\Queries\FindAllProductsQuery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->success((new FindAllProductsQuery())->handle());
        } catch (Exception $e) {
            return response()->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'price' => 'required|numeric|min:0',
                'description' => 'required',
            ];

            // Validar o request
            $validator = Validator::make($request->all(), $rules);

            // Verificar se a validação falhou
            if ($validator->fails()) {
                // Se a validação falhar, lançar uma exceção com as mensagens de erro
                throw new \InvalidArgumentException($validator->errors()->first());
            }
            $productDto = ProductDto::fromRequest($request);
            $product = (new StoreProductCommand($productDto))->execute();
            return response()->success($product, Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            return response()->error($invalidArgumentException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
