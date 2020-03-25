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

//验证码
route::get('/code/captcha/{tmp}','Admin\LoginController@captcha');

//后台不要验证的组
Route::group(['prefix'=>'admin','namespace'=>'Admin'],function (){
    //后台发送确认login
    route::post('dologin','LoginController@doLogin');
    //加密测试
    Route::get('jiami','LoginController@jiami');
    //后台登录路由
    Route::get('login','LoginController@login');
});

//后台需要登录验证的组
Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'IsLogin'],function (){
    //后台首页路由
    Route::get('index','LoginController@index');
    //后台欢迎页面路由
    Route::get('welcome','LoginController@welcome');
    //后台退出登录路由
    Route::get('logout','LoginController@logout');

    //后台用户模块相关路由
    Route::resource('user','UserController');

});