<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Store $store)
    {
        $this->authorize('view', $store);
        $products = $store->products;
        return $this->sendResponse($products, 200, 'Products get successfully.');
    }

    public function store(Request $request, Store $store)
    {
        $this->authorize('update', $store);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = $store->products()->create($request->all());

        return $this->sendResponse($product, 201, 'Product created successfully.');
    }

    public function show(Store $store, Product $product)
    {
        $this->authorize('view', $store);
        return $this->sendResponse($product, 200, 'Product get successfully.');
    }

    public function update(Request $request, Store $store, Product $product)
    {
        $this->authorize('update', $store);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $product->update($request->all());

        return $this->sendResponse($product, 200, 'Product updated successfully.');
    }

    public function destroy(Store $store, Product $product)
    {
        $this->authorize('delete', $store);
        $product->delete();

        return $this->sendResponse(null, 204, 'Product deleted successfully.');
    }
}