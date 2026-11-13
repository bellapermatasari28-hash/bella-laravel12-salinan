<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $rating = Rating::create([
            'customer_id'    => $request->user()->id,
            'product_id'     => $request->product_id,
            'transaction_id' => $request->transaction_id,
            'rating'         => $request->rating,
            'review'         => $request->review,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success Add Rating',
            'data'    => $rating
        ], 201);
    }
}