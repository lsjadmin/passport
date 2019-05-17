<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return view('welcome');
});

//用户接口
Route::post('/user/reg','User\UserController@reg'); //接受 lumen api传过来的注册信息
Route::post('/user/login','User\UserController@login'); //接受 lumen api传过来的登陆信息
Route::get('/user/user','User\UserController@user'); //接受 lumen 个人中心穿过来的信息

Route::get('/user/goodslist','User\UserController@goodslist'); //接受 lumen 个人中心穿过来的信息
Route::get('/user/cara','User\UserController@cara'); //接受 lumen 加入购物车过来的信息
Route::get('/user/carlist','User\UserController@carlist'); //接受 lumen 购物车展示过来的信息

Route::get('/user/order','User\UserController@order'); //接受 lumen 订单展示过来的信息

Route::get('/user/orderlist','User\UserController@orderlist'); //接受 lumen 订单展示过来的信息
//支付宝支付

Route::get('/user/ordera','User\UserController@ordera'); //支付宝支付

Route::get('/ali/ali','Ali\AliControler@pay'); //接受 lumen 订单展示过来的信息
Route::get('/ali/notify','Ali\AliControler@notify'); //微信支付异步
Route::get('/ali/aliReturn','Ali\AliControler@aliReturn'); //微信支付同步
