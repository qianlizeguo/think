<?php

/**
 * 后台登录页
 */

namespace app\admin\controller;

use \think\Request;
use \think\Db;
use \think\Session;
use \think\Controller;

class LoginController
{
    private $request;
    
    //初始化
    public function __construct()
    {
    }

    //登录页
    public function index() 
    {
        \think\View::share('shop_name', $GLOBALS['system_config']['SHOP_NAME']);       

        return view();
    }

    //提交登录页
    //登录成功后跳转到欢迎页
    public function user_login(Request $request)
    {
        $param = $request->post();

        $user_service_obj = new \app\admin\service\UserService();
        return $user_service_obj->userLoginService($param);
    }

    //退出登录
    public function login_out()
    {
        Session::clear();

        $admin_module = \think\Config::get('admin_module');
        $url = \think\Url::build($admin_module . '/Login/index');

        return redirect($url);
    }
}
