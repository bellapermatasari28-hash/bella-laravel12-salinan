<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')->latest();

        if ($request->has('search')) {
            $products->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $products->where('category_id', $request->category_id);
        }

        return response()->json([
            'success' => true,
            'message' => 'List Data Products',
            'data'    => $products->paginate(10)
        ], 200);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'ratings.customer'])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Product',
            'data'    => $product
        ], 200);
    }
}