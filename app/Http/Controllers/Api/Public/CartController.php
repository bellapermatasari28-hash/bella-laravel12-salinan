<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $carts = Cart::with('product')
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Carts',
            'data'    => $carts
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $cart = Cart::where('customer_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $cart->increment('qty', $request->qty);
        } else {
            $cart = Cart::create([
                'customer_id' => $request->user()->id,
                'product_id'  => $request->product_id,
                'qty'         => $request->qty,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success Add To Cart',
            'data'    => $cart
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $cart = Cart::where('customer_id', $request->user()->id)->find($id);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart Not Found',
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Success Delete Cart Item',
        ], 200);
    }
}