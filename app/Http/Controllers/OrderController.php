<?php

namespace App\Http\Controllers;

use App\Ecommerce\Product\OrderService;
use App\Ecommerce\Product\ProductStatic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->validate($request, ProductStatic::productOrderValidation());
            $data = $request->only('product_id', 'quantity');
            $this->orderService->setUser($request->user())->storeOrder($data);
            return response()->json(["message" => "Success"], 201);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function myOrders(Request $request)
    {
        try {
            $data = $this->orderService->setUser($request->user())->getMyOrders();
            return response()->json(["message" => "Success", "data" => $data]);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        } catch (\Throwable $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
