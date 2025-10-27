<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role == 'staff') {
            //jika SUDAH login dan role staff
            //return next : memperbolehkan untk melanjjutkan akses ke halaman
        return $next($request);
        }else{
            //jika bukan staff dibalikkin ke home
            return redirect()->route('home');
        }
    }
}