<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($items->map(function ($item) {
            return [
                'id' => $item->product_id,
                'cart_item_id' => $item->id,
                'name' => $item->product->name,
                'price' => (float)$item->product->selling_price,
                'quantity' => $item->quantity,
                'stock' => $item->product->quantity_in_stock,
            ];
        }));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $validated['product_id']],
            ['quantity' => $validated['quantity']]
        );

        return response()->json($cartItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->firstOrFail();

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json($cartItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($productId)
    {
        CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Clear the entire cart for the user.
     */
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }
}
