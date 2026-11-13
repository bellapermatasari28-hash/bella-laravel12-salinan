<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function notification(Request $request)
    {
        $payload = $request->all();
        
        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $signatureKey = $payload['signature_key'];
        $serverKey = config('services.midtrans.server_key');

        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $mySignatureKey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Signature Key',
            ], 400);
        }

        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        $transaction = Transaction::where('invoice', $orderId)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction Not Found',
            ], 404);
        }

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->update(['status' => 'pending']);
            } else if ($fraudStatus == 'accept') {
                $transaction->update(['status' => 'success']);
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->update(['status' => 'success']);
        } else if ($transactionStatus == 'pending') {
            $transaction->update(['status' => 'pending']);
        } else if ($transactionStatus == 'deny') {
            $transaction->update(['status' => 'failed']);
        } else if ($transactionStatus == 'expire') {
            $transaction->update(['status' => 'expired']);
        } else if ($transactionStatus == 'cancel') {
            $transaction->update(['status' => 'failed']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification Success Processed',
        ], 200);
    }
}