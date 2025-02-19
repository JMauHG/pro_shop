<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = Auth::user()->carts;
        return $this->sendResponse($carts, 200, 'Cart get successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $store = Auth::user()->carts()->create($request->all());

        return $this->sendResponse($store, 201, 'Cart created successfully.');
    }

    public function show(Cart $cart)
    {
        $this->authorize('view', $cart);
        return $this->sendResponse($cart, 200, 'Cart get successfully.');
    }

    public function update(Request $request, Cart $cart)
    {
        $this->authorize('update', $cart);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $cart->update($request->all());

        return $this->sendResponse($cart, 200, 'Cart updated successfully.');
    }

    public function destroy(Cart $cart)
    {
        $this->authorize('delete', $cart);
        $cart->delete();

        return $this->sendResponse(null, 204, 'Cart deleted successfully.');
    }

    public function addProduct(Request $request, Product $product)
    {
        $user = Auth::user();
        $status = Status::where('type', 'cart')->where('name', 'pending')->first();
        $cart = Cart::firstOrCreate(['user_id' => $user->id, 'status_id' => $status->id]);
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $totalQuantity = $cartItem->quantity + $request->quantity;
            if ($request->quantity > $product->stock) {
                return $this->sendError('Unauthorised.', ['error' => 'Not enough stock'], 400);
            }
            $cartItem->update(['quantity' => $totalQuantity]);
        } else {
            if ($request->quantity > $product->stock) {
                return $this->sendError('Unauthorised.', ['error' => 'Not enough stock'], 400);
            }

            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        $product->stock -= $request->quantity;
        $product->save();

        return $this->sendResponse($cart->load('cartItems.product'), 200, 'Cart items updated successfully.');
    }

    public function removeProduct(Request $request, Product $product)
    {
        $user = Auth::user();
        $status = Status::where('type', 'cart')->where('name', 'pending')->first();
        $cart = Cart::where('user_id', $user->id)->where('status_id', $status->id)->first();

        if (!$cart) {
            return $this->sendError('Unauthorised.', ['error' => 'Cart not found'], 400);
        }

        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();
        if (!$cartItem) {
            return $this->sendError('Unauthorised.', ['error' => 'Product not in cart'], 400);
        }

        if ($request->quantity < $cartItem->quantity) {
            $cartItem->update(['quantity' => $cartItem->quantity - 1]);
        } else {
            $cartItem->delete();
        }

        $product->stock += $request->quantity;
        $product->save();

        return $this->sendResponse($cart->load('cartItems.product'), 200, 'Cart item updated successfully.');
    }
}
