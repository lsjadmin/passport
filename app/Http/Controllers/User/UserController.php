<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Model\OrderModel;
class UserController extends Controller
{
    //接受 lumen api传过来的注册信息q
    public function reg(){
        $info=$_POST;
        $res=DB::table('p_api')->insert($info);
        if($res){
            $arr=[
                'res'=>200,
                'msg'=>'注册成功'
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }else{
            $arr=[
                'res'=>40001,
                'msg'=>'注册失败'
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
       
    }
    //接受 lumen api传过来的登陆信息
    public function login(){
        $arr=$_POST;
        //print_r($info);
        $pass=$arr['password'];
        $where=[
            'email'=>$arr['useremail']
        ];
        $res=DB::table('p_api')->where($where)->first();
        if($res){
            if($pass==$res->pass){
                $token=$this->postlogintoken($res->api_id);
                $arr=[
                    'res'=>200,
                    'msg'=>'登陆成功',
                    'api_id'=>$res->api_id,
                    'data'=>[
                        'token'=>$token,
                    ]
                ];
                $key="lumen_login_token.$res->api_id";
                Redis::set($key,$token);
                Redis::expire($key,604800);

                return json_encode($arr,JSON_UNESCAPED_UNICODE);
            }else{
                $arr=[
                    'res'=>50001,
                    'msg'=>'登陆失败'
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);
            }
        }else{
            $arr=[
                'res'=>50000,
                'msg'=>'没有这个用户'
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
    }
    //个人中心
    public function user(){
        $id=$_GET['id'];
      // echo $uid;
      $where=[
        'api_id'=>$id
         ];
     $res=DB::table('p_api')->where($where)->first();
        // dd($res);
        if($res){
            $arr=[
                'res'=>200,
                'msg'=>'获得用户信息成功',
                'name'=>$res->name,
                'email'=>$res->email
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }else{
            $arr=[
                'res'=>40000,
                'msg'=>'失败',

            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
    }
    //获得token
      public function postlogintoken($id){
        $str=Str::random(10);
        $token=substr(sha1(time().$id.$str),5,15);
        return $token;
    }
    //商品详情
        public function goodslist(){
            $id=$_GET['id'];
            //dd($id);
           $where=[
               'g_id'=>$id
           ];
           $data=DB::table('goods')->where($where)->first();
          // dd($data);
           if($data){
                $arr=[
                    'res'=>200,
                    'msg'=>'获得商品信息成功',
                    'goods_name'=>$data->goods_name,
                    'goods_price'=>$data->goods_price,
                    'g_id'=>$data->g_id
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);
           }else{
                $arr=[
                    'res'=>200,
                    'msg'=>'获得商品信息失败'
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);
           }
        }
    //购物车
        public function cara(){
            $g_id=$_GET['id'];
            $api_id=$_GET['api_id'];
            $where=[
                'g_id'=>$g_id
            ];
            $res=DB::table('goods')->where($where)->first();
            $info=[
                'goods_name'=>$res->goods_name,
                'goods_price'=>$res->goods_price,
                'goods_id'=>$res->g_id,
                'u_id'=>$api_id,
                'session_id'=>'1',
                'add_time'=>time()
            ];
            //加入购物车
            $arr=DB::table('cart')->insert($info);
            if($arr){
                $arr=[
                    'res'=>200,
                    'msg'=>'加入购物车成功'
 
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);   
            }else{
                $arr=[
                    'res'=>40001,
                    'msg'=>'加入购物车失败'
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);   
            }

           
        }
    //购物车展示
        public function carlist(){
            $u_id=$_GET['u_id'];
            //echo $u_id;
             $carwhere=[
                'u_id'=>$u_id,
                'is_status'=>0
            ];
            $car=DB::table('cart')->where($carwhere)->get();
            //dd($car);
            if($car){
                echo json_encode($car,JSON_UNESCAPED_UNICODE);
            }else{
                $arr=[
                    'res'=>40001,
                    'msg'=>'获得购物车信息失败'
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);   
            }
        }
    //生成订单
        public function order(){
            DB::beginTransaction(); //开启事务
            $u_id=$_GET['u_id'];
            $price=$_GET['price'];
            $g_id=$_GET['g_id'];
            //添加订单表
            $info=[
                'u_id'=>$u_id,
                'order_sn'=>$this->ordersn(),
                'order_amount'=>$price,
                'add_time'=>time(),
            
            ];
            //获得订单id
            $id=OrderModel::insertGetId($info);
            //echo $id;
            $goodsinfo=DB::table('goods')->where(['g_id'=>$g_id])->first();
            $carinfo=[
                'o_id'=>$id,
                'goods_id'=>$g_id,
                'goods_name'=>$goodsinfo->goods_name,
                'goods_price'=>$goodsinfo->goods_price,
               
            ];
            //添加订单商品表
            $res=DB::table('order_detail')->insert($carinfo);
            if($id&&$res){
                 //如果语句执行成功就进行提交
                    DB::commit();
                    $arr=[
                        'res'=>200,
                        'msg'=>'生成订单成功'
                    ];
                    return json_encode($arr,JSON_UNESCAPED_UNICODE);   
            }else{
                //如果失败就回滚
                DB::rollback();
                $arr=[
                    'res'=>40001,
                    'msg'=>'生成订单失败'
                ];
                return json_encode($arr,JSON_UNESCAPED_UNICODE);   
            }
        }
    //生成订单号
       function ordersn(){

        $str=Str::random(10);
        $a=substr(md5($str.time()),5,10);
        return $a;
    }
    //订单展示
        public function orderlist(){
            $u_id=$_GET['u_id'];
            //echo $u_id;
            $where=[
                'u_id'=>$u_id,
                'is_status'=>0
            ];

           $arr= DB::table('order')
            ->join('order_detail', 'order.o_id', '=', 'order_detail.o_id')
            ->select('order_sn', 'goods_name', 'goods_price')
            ->where($where)
            ->get();
            //dd($res);
       
           echo  json_encode($arr,JSON_UNESCAPED_UNICODE);
           
        }
    //支付宝支付
        public function ordera(){
            //接受订单号
            $order=$_GET['order'];
           // dd($order);
            return view('order.order',['order'=>$order]);
        }

    

}
