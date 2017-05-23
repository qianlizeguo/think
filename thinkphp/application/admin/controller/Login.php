<?php

/**
 * 后台登录页
 */

namespace app\admin\controller;

use \think\Request;
use \think\Db;

class Login 
{
    private $request;
    private $config_info;
    
    //初始化
    public function __construct()
    {
        $this->config_info = get_system_config();
    }

    //登录页
    public function index() 
    {
        \think\View::share('shop_name', $this->config_info['SHOP_NAME']);       

        return view();
    }

    //提交登录页
    //登录成功后跳转到欢迎页
    public function user_login(Request $request)
    {
        $param = $request->post();
        //if ($param['loginName'] && $param['password']) {
        //    $where = 'username = "' . $param['loginName'] . '"';
        //    $user_info = Db::name('users')->where($where)->field('user_id, username, role_type, password, is_enable, login_try_times, block_time, group_id')->find();
        //    if (!$user_info) return -1;

        //    if ($user_info['is_enable'] == 2) return -3;

        //    $cur_time = time();
        //} 

        $user_service_obj = new \app\admin\service\UserService();
        $user_info = $user_service_obj->get_user_info($param);

        if (!$user_info) return -1;

        if ($user_info['is_enable'] == 2) return -3;

        if ($user_info['password'] == md5($param['password'])) return 0;
    }
}
