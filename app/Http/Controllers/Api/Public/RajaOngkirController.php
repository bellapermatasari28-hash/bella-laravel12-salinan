<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    public function provinces()
    {
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.key'),
        ])->get('https://api.rajaongkir.com/starter/province');

        return response()->json([
            'success' => true,
            'message' => 'List Data Provinces',
            'data'    => $response['rajaongkir']['results']
        ], 200);
    }

    public function cities($province_id)
    {
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.key'),
        ])->get('https://api.rajaongkir.com/starter/city?province=' . $province_id);

        return response()->json([
            'success' => true,
            'message' => 'List Data Cities',
            'data'    => $response['rajaongkir']['results']
        ], 200);
    }

    public function checkOngkir(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('services.rajaongkir.key'),
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin'      => $request->origin,
            'destination' => $request->destination,
            'weight'      => $request->weight,
            'courier'     => $request->courier,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check Cost Success',
            'data'    => $response['rajaongkir']['results']
        ], 200);
    }
}