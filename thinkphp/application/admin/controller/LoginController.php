<?php

/**
 * 后台登录页
 */

namespace app\admin\controller;

use \think\Controller;
use \think\Request;
use \think\Db;
use \think\Session;

class LoginController extends GlobalController
{
    //初始化
    public function _initialize()
    {
        //parent::_initialize();
    }

    //登录页
    public function index() 
    {
        return $this->fetch();
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

        $url = \think\Url::build(ADMIN_MODULE. '/Login/index');

        return $this->redirect($url);
    }
}
