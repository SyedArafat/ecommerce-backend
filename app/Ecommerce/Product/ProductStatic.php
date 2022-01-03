<?php

namespace App\Ecommerce\Product;

class ProductStatic
{
    public static function productValidation($for_update = 0): array
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric|max:99999999',
            'quantity' => 'required|numeric|max:10',
            'image' => $for_update ? 'sometimes|file|mimes:jpeg,jpg,png,gif|required|max:10000' : 'required|file|mimes:jpeg,jpg,png,gif|required|max:10000'
        ];
    }

    public static function productOrderValidation(): array
    {
        return ["product_id" => "required", "quantity" => "required"];
    }
}
