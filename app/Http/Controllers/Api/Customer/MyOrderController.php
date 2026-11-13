<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['details.product'])
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Transactions',
            'data'    => $transactions
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $transaction = Transaction::with(['details.product', 'customer'])
            ->where('customer_id', $request->user()->id)
            ->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction Not Found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Transaction',
            'data'    => $transaction
        ], 200);
    }
}