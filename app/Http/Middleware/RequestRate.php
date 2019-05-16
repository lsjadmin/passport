<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
class RequestRate
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
        //dd(session::getId());
       
        //dd($_SERVER['REMOTE_ADDR']);
        $ip=$_SERVER['REMOTE_ADDR'];
        $token=request()->input('token');
        $key="request_time10_ip:".$ip.'token:'.$token;
        //echo $key;echo"<hr>";
        $a=Redis::get($key);
        echo"redis:";echo $a;echo"<br>";
        if($a>=10){  //限制10次
            die("超过限制次数");
        }
        Redis::incr($key); //自增
        Redis::expire($key,10);
        echo date('Y-m-d H-i-s');echo"<br>";
        return $next($request);
    }
}
