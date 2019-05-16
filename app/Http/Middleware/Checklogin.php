<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class Checklogin
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
        $token=request()->input('token');
        $id=request()->input('id');
        //判断是否缺少参数
        if(empty($token) || empty($id)){
            $response=[
                'errno'=>'40003',
                'msg'=>'缺少参数'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        //验证token
        $key="lar_login_token.$id";
        $redis_token=Redis::get($key);
       // echo $redis_token;echo"<hr>";
        // if($redis_token){
        //         if($redis_token==$token){
                        
        //         }else{
        //             $response=[
        //                 'errno'=>'40005',
        //                 'msg'=>'token不一致'
        //             ];
        //             die(json_encode($response,JSON_UNESCAPED_UNICODE));
        //         }
        // }else{
        //     $response=[
        //         'errno'=>'40004',
        //         'msg'=>'没有token'
        //     ];
        //     die(json_encode($response,JSON_UNESCAPED_UNICODE));
        // }
        // return $next($request);
    }
}
