<?php

namespace App\Http\Controllers\Admin;

use App\Model\User;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //后台登录页面
    public function login()
    {
      return view('admin.login');
    }

    //后台首页
    public function index()
    {
        return view('admin.index');
    }

    //后台的welcome页面
    public function welcome()
    {
        return view('admin.welcome');
    }

    //退出登录
    public function logout()
    {
        //清空session中的用户信息
        session()->flush();
        //跳转到登录页面
        return redirect('admin/login');
    }

     //验证码模块
    public function captcha($tmp){
        $phrase = new PhraseBuilder;
        //设置验证吗位数
        $code = $phrase->build(6);
        //生成验证码图片build 对象 ，配置相应属性
        $builder = new CaptchaBuilder($code,$phrase);
        //设置背景颜色
        $builder->setBackgroundColor(220,210,230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        //可以设置图片宽度及字体
        $builder->build($whth=100,$height=40,$font=null);
        //获取验证码内容
        $phrase = $builder->getPhrase();
        //把内容存入session
        \Session::flash('code',$phrase);
        //生成图片
        header('Cache-Control:no-cache,must-revalidate');
        header('Cotent-Type:image/jpeg');
        $builder ->output();
    }

    //处理用户登录到方法
    public function doLogin (Request $request)
    {
//        1.接收表单提交的数据
        $input = $request->except('_token');

//        2.进行表单验证
 //       $validator = Validator::make('需要验证的表单数据','验证规则','错误提示信息');
        $rule=[
            'phone'=>'required|min:11',
            'password'=>'required|between:6,18'
        ];
        $msg = [
            'phone.required'=>'电话号码必须输入',
            'phone.min'=>'电话号码必须为11位',
            'password.required'=>'密码必须输入',
            'password.between'=>'密码必须为6-18位',
        ];
        $validator = Validator::make($input,$rule,$msg);
        if ($validator->fails()) {
            return redirect('admin/login')
                ->withErrors($validator)
                ->withInput();
        }
//        3.验证是否有此用户(手机号码 密码 验证码是否一致)
        //验证验证码是否正确
        if(strtolower($input['code']) != strtolower(session()->get('code')))
        {
            return redirect('admin/login')->with('errors','验证码错误');
        }

        $user = User::where('phone',$input['phone'])->first();
        //手机号码是否存在
        if(!$user){
            return redirect('admin/login')->with('errors','用户不存在');
        }
        //密码是否正确
        if(Crypt::decrypt($user->user_pass)!=$input['password']){
            return redirect('admin/login')->with('errors','密码错误');
        }

//        4.保存用户信息到session中
        session()->put('user',$user);

//        5.跳转到后台首页

        return redirect('admin/index');
    }

    //加密算法
    public function jiami()
    {
//        1.md5加密，生成一个32位的字符串 ，一般会加个前缀作为保险
//         $str = 'blog'.'123456';
//         return md5($str);

//         2.哈希加密,每次生成的加密结果都不同，使用check进行核对
//         $str = '1234';
//         $hash = Hash::make($str);
//         if(Hash::check($str,$hash)){
//             return '密码正确';
//         }else{
//             return '密码错误';
//         }

//          3.crypt加密  生成一个255位的字符串且每次生成都会改变
//           $str = '123456';
//           $crypt_str = Crypt::encrypt($str); //生成crypt密码
//           return $crypt_str;
//             $crypt_str='eyJpdiI6IkQrS01wUmdnMHNMM056UU1WNldUZ1E9PSIsInZhbHVlIjoiQWd1V0RGQ1Fyc0ZuTnpCclZHT2hKdz09IiwibWFjIjoiNWMyODU5NzE0OWUzOTc3NmNiNzg2MmE4ZGM4ZTRmMTMwMjM5MzUzNzU0MDk5ZWM1NzRlZmFkOTJkY2E4N2VkOCJ9';
//             if(Crypt::decrypt($crypt_str)==$str){
//                 return "密码正确";
//             }
    }

}
