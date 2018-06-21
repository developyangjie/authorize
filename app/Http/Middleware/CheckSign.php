<?php
/**
 * Created by PhpStorm.
 * User  :  liulei
 * Date  :  2017/8/4
 * Time  :  16:29
 * Email :  369968620@163.com
 */
namespace App\Http\Middleware;

use Closure;
use Request;



class CheckSign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $post_data = $request->request->all();
        $ret = app('CheckSign')->checkSign($post_data);

        if($ret['state']){
            return $next($request);
        }else{
            return response(['code'=>"400","msg"=>$ret['msg']])
                ->header('status', 200);
        }
    }
}
