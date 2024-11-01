<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlogOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $blogid=$request->route('blog');

        $blog=Blog::findorfail($blogid);
  
        if(Auth::guard('sanctum')->user()->id==$blog->user_id){
            return $next($request);
        }
        else{
            return forbidden();
        }
       
    }
}
