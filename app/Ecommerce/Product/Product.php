<?php

namespace App\Ecommerce\Product;

use App\Ecommerce\Traits\FileUpload;
use Illuminate\Http\Request;

class Product
{
    use FileUpload;

    private $storeData;

    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function makeStoreData(Request $request): Product
    {
        $this->storeData = $this->makeData($request);
        return $this;
    }

    public function storeProduct()
    {
        return $this->productRepository->store($this->storeData);
    }

    public function getData(Request $request)
    {
        $products = $this->productRepository->query();
        if(isset($request->name) && $request->name !== 'null') $products = $this->productRepository->filterByName($products, $request->name);
        if($request->filter_by_price) $products = $this->productRepository->filterByPrice($products, $request->filter_by_price);
        return $products->get();
    }

    /**
     * @param Request $request
     * @return array
     */
    private function makeData(Request $request): array
    {
        $data = $request->only(['name', 'description', 'price', 'quantity']);
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $image = $this->storeFile($file, null, 'product');
            $data = array_merge($data, ["image" => $image]);
        }
        return $data;
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateProduct(Request $request, $id)
    {
        $data = $this->makeData($request);
        $product = $this->productRepository->show($id);
        if($request->hasFile('image')) $this->deleteImage($product->image);
        $this->productRepository->update($data, $id);
    }

    public function deleteImage($image)
    {
        $this->deleteFile($image);
    }
}
