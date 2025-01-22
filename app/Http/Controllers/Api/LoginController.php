<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validate request data
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|string',
                'password' => 'required|string'
            ]
        );

        // If validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get credentials from request
        $credentials = $request->only('username', 'password');

        // Attempt to authenticate the user
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password Anda salah'
            ], 401);
        }

        // Authentication successful
        return response()->json([
            'success' => true,
            'user' => Auth::guard('api')->user(),
            'token' => $token
        ], 200);
    }
}
