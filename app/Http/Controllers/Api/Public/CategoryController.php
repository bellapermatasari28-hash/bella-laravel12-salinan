<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Categories',
            'data'    => $categories
        ], 200);
    }
}
