<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            // Invalidate token
            $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

            if ($removeToken) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logout Berhasil!',
                ], 200);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Logout! Token tidak valid atau sudah kadaluarsa.',
            ], 401);
        }
    }
}
