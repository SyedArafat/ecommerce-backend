<?php

namespace App\Http\Controllers;

use App\Ecommerce\Product\Product;
use App\Ecommerce\Product\ProductRepository;
use App\Ecommerce\Product\ProductStatic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    private $productRepo;

    /**
     * @var Product
     */
    private $product;

    public function __construct(ProductRepository $productRepo, Product $product)
    {
        $this->productRepo = $productRepo;
        $this->product     = $product;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $products = $this->product->getData($request);
            return response()->json(['data' => $products, 'message' => 'success']);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->validate($request, ProductStatic::productValidation());
            $product = $this->product->makeStoreData($request)->storeProduct();
            return response()->json(['message'=>'success', 'data'=>$product], 201);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->productRepo->show($id);
            return response()->json(['data' => $data, 'message' => 'success']);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $this->validate($request, ProductStatic::productValidation(1));
            $this->product->updateProduct($request, $id);
            return response()->json(['message' => 'success']);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $product = $this->productRepo->show($id);
            if($product) {
                $this->product->deleteImage($product->image);
                $this->productRepo->delete($id);
            }
            return response()->json(['message' => 'success']);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
