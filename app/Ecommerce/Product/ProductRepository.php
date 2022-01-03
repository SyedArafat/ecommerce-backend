<?php

namespace App\Ecommerce\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository
{
    public function all()
    {
        return Product::get();
    }

    public function store($data)
    {
        return Product::create($data);
    }

    public function show($id)
    {
        return Product::find($id);
    }

    public function query(): Builder
    {
        return Product::query();
    }

    /**
     * @param $query
     * @param $name
     * @return Builder
     */
    public function filterByName($query, $name): Builder
    {
        return $query->where('name', 'like', "%$name%");
    }

    /**
     * @param $query
     * @param $order
     * @return mixed
     */
    public function filterByPrice($query, $order)
    {
        return $query->orderBy('price', $order);
    }

    public function update($data, $id)
    {
        return Product::query()->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $product = $this->show($id);
        $product->delete();
    }
}
