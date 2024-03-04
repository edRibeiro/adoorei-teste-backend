<?php

namespace App\Http\Controllers\Api;

use App\Domain\Exceptions\EntityNotFoundException;
use App\Dtos\SaleDto;
use App\Http\Controllers\Controller;
use App\Mappers\SaleMapper;
use App\UseCases\Sales\Commands\AddProductFromSaleCommand;
use App\UseCases\Sales\Commands\DestroySaleCommand;
use App\UseCases\Sales\Commands\StoreSaleCommand;
use App\UseCases\Sales\Queries\FindAllSalesQuery;
use App\UseCases\Sales\Queries\FindSaleByIdQuery;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SaleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sales",
     *     summary="Retorna todas as vendas registradas",
     *     tags={"Sales"},
     *     @OA\Response(
     *         response=200,
     *         description="Operação bem sucedida",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Sale")
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
            return response()->success((new FindAllSalesQuery())->handle());
        } catch (Exception $e) {
            return response()->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/sales",
     *     summary="Cria uma nova venda",
     *     tags={"Sales"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados da venda",
     *         @OA\JsonContent(
     *             required={"name", "price", "amount", "products"},
     *             @OA\Property(property="name", type="string", example="Venda de Teste"),
     *             @OA\Property(property="price", type="number", format="float", example=100.50),
     *             @OA\Property(property="amount", type="integer", example=2),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"product_id", "name", "price", "amount"},
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Produto A"),
     *                     @OA\Property(property="price", type="number", format="float", example=50.00),
     *                     @OA\Property(property="amount", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Venda criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Sale")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Entidade não processável",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="The products field is required.")
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
            $newSale = SaleMapper::fromRequest($request);
            $sale = (new StoreSaleCommand($newSale))->execute();
            return response()->success($sale, Response::HTTP_CREATED);
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/sales/{id}",
     *     summary="Retorna uma venda específica",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da venda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operação bem-sucedida",
     *         @OA\JsonContent(ref="#/components/schemas/Sale")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venda não encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            return response()->success((new FindSaleByIdQuery($id))->handle());
        } catch (ResourceNotFoundException $e) {
            return response()->error($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/sales/{id}",
     *     summary="Atualiza uma venda existente",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da venda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados da venda",
     *         @OA\JsonContent(
     *             required={"products"},
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"product_id", "amount"},
     *                     @OA\Property(property="product_id", type="integer", example=2),
     *                     @OA\Property(property="amount", type="integer", example=9)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Venda atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Sale")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venda não encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function update(Request $request, int $id)
    {
        try {
            $sale = SaleDto::fromRequest($request, $id);
            (new AddProductFromSaleCommand($sale))->execute();
            return response()->success((new FindSaleByIdQuery($id))->handle());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/sales/{id}",
     *     summary="Exclui uma venda existente",
     *     tags={"Sales"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da venda a ser excluída",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Venda excluída com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Venda não encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            (new DestroySaleCommand($id))->execute();
            return response()->success(null, Response::HTTP_NO_CONTENT);
        } catch (EntityNotFoundException $e) {
            return response()->error($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
