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
        // 1️⃣ BYPASS OPTIONS biar CORS tidak mati
        if ($request->getMethod() === 'OPTIONS') {
            return response('OK', 200, [
                'Access-Control-Allow-Origin'      => $request->headers->get('Origin') ?? '*',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers'     => 'Origin, Content-Type, Accept, Authorization, X-Requested-With',
            ]);
        }

        // 2️⃣ JIKA BELUM LOGIN
        if (!Auth::guard('admin')->check()) {

            // ❗ INI PENTING: request API JANGAN DI-REDIRECT
            if ($request->expectsJson() || $request->is('admin/transport/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated API request.'
                ], 401, [
                    'Access-Control-Allow-Origin'      => $request->headers->get('Origin') ?? '*',
                    'Access-Control-Allow-Credentials' => 'true',
                ]);
            }

            // Browser biasa redirect
            return redirect()->route('admin.login');
        }

        // 3️⃣ LANJUTKAN REQUEST
        $response = $next($request);

        // 4️⃣ TAMBAHKAN HEADER CORS DI AKHIR RESPONSE
        $origin = $request->headers->get('Origin') ?? '*';

        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
