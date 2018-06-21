<?php

namespace App\Http\Middleware;

use Closure;

class AuthLogin
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
        $authorization_code = session('admin_user_info.auth_code');
        if(!$authorization_code){
            return redirect()->route('authView');
        }
        return $next($request);
    }
}
