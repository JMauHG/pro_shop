<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders;
        return $this->sendResponse($orders, 200, 'Orders get successfully.');
    }

    public function completePurchase(Cart $cart)
    {
        $user = Auth::user();

        $createdStatus = Status::where('type', 'order')->where('name', 'created')->first();
        $pendingStatus = Status::where('type', 'cart')->where('name', 'pending')->first();
        $completedStatus = Status::where('type', 'cart')->where('name', 'completed')->first();

        if ($cart->user_id !== $user->id) {
            return $this->sendError('Unauthorised.', ['error' => 'Cart does not belong to user'], 401);
        }

        if ($cart->status_id !== $pendingStatus->id) {
            return $this->sendError('Unauthorised.', ['error' => 'Cart in not peding to purchase'], 401);
        }

        if ($cart->cartItems->isEmpty()) {
            return $this->sendError('Unauthorised.', ['error' => 'Cart items are empty'], 400);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'status_id' => $createdStatus->id
        ]);

        foreach ($cart->cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price
            ]);
        }

        $cart->update(['status_id' => $completedStatus->id]);

        return $this->sendResponse($order->load('orderItems.product'), 201, 'Order created successfully.');
    }
}
