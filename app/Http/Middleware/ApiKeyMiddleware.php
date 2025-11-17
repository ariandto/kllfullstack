<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil API_KEY dari header request
        $apiKey = $request->header('API_KEY');
        //dd($apiKey);
        // Log API_KEY dari request dan konfigurasi
        Log::info('API_KEY dari header:', ['apiKey' => $apiKey]);
        Log::info('API_KEY dari config:', ['configApiKey' => config('api.api_key')]);

        // Cek apakah API_KEY cocok dengan yang ada di konfigurasi
        if ($apiKey !== config('api.api_key')) {
            Log::info('API_KEY mismatch atau tidak diberikan');
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
