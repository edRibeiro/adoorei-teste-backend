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
     * @OA\Get(
     *     path="/api/products",
     *     summary="Retorna todos os produtos cadastrados",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Operação bem-sucedida",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/products",
     *     summary="Cria um novo produto",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do produto",
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Produto A"),
     *             @OA\Property(property="price", type="number", format="float", example=50.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Entidade não processável",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="O campo name é obrigatório.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
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
