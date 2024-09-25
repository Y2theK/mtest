<?php

namespace App\Http\Middleware;

use App\Enums\User\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->role === UserType::ADMIN->value){
            return $next($request);
        }else{
            abort(403,'You cannot access this page');
        }
    }
}
