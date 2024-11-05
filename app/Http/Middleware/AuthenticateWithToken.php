<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        //  if ($token = $request->cookie('auth_token')) {
            
        //     $request->headers->set('Authorization', 'Bearer ' . $token);
        // }
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        // if(!$request->cookie('user_id')==Auth::guard('sanctum')->user()->id){
        //     return forbidden();
        // }

        return $next($request);
    
    }
}
