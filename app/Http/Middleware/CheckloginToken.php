<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class CheckloginToken
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
        
        if(empty($_COOKIE['token'])||empty($_COOKIE['id'])){
           header('Refresh:2;url=http://passport.api.com/user/add');
           die('参数不对，2秒返回登陆页面');
        }
        $id=$_COOKIE['id'];
        $token=$_COOKIE['token'];
        //echo $id;echo"<br>";
        $key="login_token.$id";
        $redis_token=Redis::get($key);
         echo $redis_token;echo'<br>';
       if($redis_token){
                if($token==$redis_token){
                        die('登陆成功');
                }else{
                    die('token不一致');
                }
       }else{
        die('没有token');
       }
        return $next($request);
    }
}
