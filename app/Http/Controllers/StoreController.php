<?php
namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Auth::user()->stores;
        return response()->json($stores);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $store = Auth::user()->stores()->create($request->all());

        return response()->json($store, 201);
    }

    public function show(Store $store)
    {
        $this->authorize('view', $store);
        return response()->json($store);
    }

    public function update(Request $request, Store $store)
    {
        $this->authorize('update', $store);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $store->update($request->all());

        return response()->json($store);
    }

    public function destroy(Store $store)
    {
        $this->authorize('delete', $store);
        $store->delete();

        return response()->json(null, 204);
    }
}