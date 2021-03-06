<?php

namespace App\Http\Middleware;

use Closure;

class MilkBarAuth
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('USER_ID') || session('USER_TYPE') != 'M') {
            return redirect('/');
        }

        return $next($request);
    }

}