<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1️⃣ HANYA BYPASS OPTIONS (jangan kasih header CORS sendiri)
        if ($request->getMethod() === 'OPTIONS') {
            return response()->json([], 200);
        }

        // 2️⃣ CEK AUTH ADMIN
        if (!Auth::guard('admin')->check()) {

            // API → jangan redirect
            if ($request->expectsJson() || $request->is('admin/transport/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthenticated API request.'
                ], 401);
            }

            // Request biasa → redirect
            return redirect()->route('admin.login');
        }

        // 3️⃣ LANJUTKAN REQUEST TANPA TAMBAH HEADER CORS
        return $next($request);
    }
}
