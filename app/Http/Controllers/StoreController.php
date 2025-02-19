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
        return $this->sendResponse($stores, 200, 'Stores get successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $store = Auth::user()->stores()->create($request->all());

        return $this->sendResponse($store, 201, 'Store created successfully.');
    }

    public function show(Store $store)
    {
        $this->authorize('view', $store);
        return $this->sendResponse($store, 200, 'Store get successfully.');
    }

    public function update(Request $request, Store $store)
    {
        $this->authorize('update', $store);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $store->update($request->all());

        return $this->sendResponse($store, 200, 'Store updated successfully.');
    }

    public function destroy(Store $store)
    {
        $this->authorize('delete', $store);
        $store->delete();

        return $this->sendResponse(null, 204, 'Store deleted successfully.');
    }
}