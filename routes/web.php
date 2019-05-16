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
