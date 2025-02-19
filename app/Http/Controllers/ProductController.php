<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Listar todos los productos de una tienda
    public function index(Store $store)
    {
        $this->authorize('view', $store); // Verificar que el vendedor es dueño de la tienda
        $products = $store->products;
        return response()->json($products);
    }

    // Crear un nuevo producto en una tienda
    public function store(Request $request, Store $store)
    {
        $this->authorize('update', $store); // Verificar que el vendedor es dueño de la tienda

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = $store->products()->create($request->all());

        return response()->json($product, 201);
    }

    // Mostrar un producto específico
    public function show(Store $store, Product $product)
    {
        $this->authorize('view', $store); // Verificar que el vendedor es dueño de la tienda
        return response()->json($product);
    }

    // Actualizar un producto
    public function update(Request $request, Store $store, Product $product)
    {
        $this->authorize('update', $store); // Verificar que el vendedor es dueño de la tienda

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    // Eliminar un producto
    public function destroy(Store $store, Product $product)
    {
        $this->authorize('delete', $store); // Verificar que el vendedor es dueño de la tienda
        $product->delete();

        return response()->json(null, 204);
    }
}