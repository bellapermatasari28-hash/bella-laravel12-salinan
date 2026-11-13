<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $length = 10;
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= rand(0, 9);
            }
            $invoice = 'TRX-' . time() . $random;

            $carts = Cart::with('product')
                ->where('customer_id', $request->user()->id)
                ->get();

            if ($carts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is Empty',
                ], 422);
            }

            $totalPrice = 0;
            $totalWeight = 0;

            foreach ($carts as $cart) {
                $totalPrice += $cart->qty * $cart->product->price;
                $totalWeight += $cart->qty * $cart->product->weight;
            }

            $transaction = Transaction::create([
                'invoice'         => $invoice,
                'customer_id'     => $request->user()->id,
                'grand_total'     => $totalPrice + $request->shipping_cost,
                'total'           => $totalPrice,
                'weight'          => $totalWeight,
                'status'          => 'pending',
                'delivery_status' => 'pending',
                'snap_token'      => '',
                'name'            => $request->name,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'city_id'         => $request->city_id,
                'province_id'     => $request->province_id,
                'courier'         => $request->courier,
                'shipping_cost'   => $request->shipping_cost,
            ]);

            foreach ($carts as $cart) {
                $transaction->details()->create([
                    'product_id' => $cart->product_id,
                    'qty'        => $cart->qty,
                    'price'      => $cart->product->price,
                ]);
            }

            $itemDetails = [];
            foreach ($carts as $cart) {
                $itemDetails[] = [
                    'id'       => $cart->product_id,
                    'price'    => $cart->product->price,
                    'quantity' => $cart->qty,
                    'name'     => $cart->product->title,
                ];
            }

            $itemDetails[] = [
                'id'       => 'SHIPPING_COST',
                'price'    => $request->shipping_cost,
                'quantity' => 1,
                'name'     => 'Shipping Cost (' . strtoupper($request->courier) . ')',
            ];

            $payload = [
                'transaction_details' => [
                    'order_id'     => $invoice,
                    'gross_amount' => $transaction->grand_total,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'email'      => $request->user()->email,
                    'phone'      => $request->phone,
                    'shipping_address' => [
                        'address'     => $request->address,
                        'city'        => $request->city_id,
                        'country_code' => 'IDN',
                    ],
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = Snap::getSnapToken($payload);

            $transaction->update([
                'snap_token' => $snapToken,
            ]);

            Cart::where('customer_id', $request->user()->id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout Success',
                'data'    => $transaction,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}