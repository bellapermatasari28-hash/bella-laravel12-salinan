<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Slider;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Sliders',
            'data'    => $sliders
        ], 200);
    }
}