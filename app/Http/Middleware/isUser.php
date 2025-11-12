<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (Auth::check() && Auth::user()->role == 'user') {
            //jika SUDAH login dan role user
            //return next : memperbolehkan untk melanjjutkan akses ke halaman
        return $next($request);
        }else{
            //jika bukan user dibalikkin ke home
            return redirect()->route('login.auth')->with('error', 'silahkan login terlebih dahulu');
        }
    }
}
