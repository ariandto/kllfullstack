<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Vendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $vendor = Auth::guard('vendor')->user();
        // dd($vendor); // Cek apakah vendor yang terautentikasi sudah sesuai
        if (!Auth::guard('vendor')->check()) { 
            return redirect()->route('vendor.login')->with('error', 'You Do Not Have Permission To Access This Page');
        }
        return $next($request);
    }
}
