<?php

namespace App\Http\Middleware;

use Closure;

class AdminLogin
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
        if(!session('admin_user_info')){
            if($request->ajax()){
                return response(['code'=>403,'msg'=>'登录已经过期'])->header('status',403);
            }
            return redirect()->route('login');
        }
        return $next($request);
    }
}
