<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfUserNotVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->confirmed) {
            return redirect('/threads')->with('flash', 'You must confirm email, before posting anything!');
        }
        
        return $next($request);
    }
}
