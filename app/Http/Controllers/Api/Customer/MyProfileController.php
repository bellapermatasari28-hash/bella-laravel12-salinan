<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MyProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Customer Profile',
            'data'    => $request->user()
        ], 200);
    }

    public function update(Request $request)
    {
        $customer = $request->user();

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $customer->name = $request->name;
        $customer->email = $request->email;

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
        }

        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Update Profile Success',
            'data'    => $customer
        ], 200);
    }
}