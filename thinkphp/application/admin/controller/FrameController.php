<?php
/**
 * 后台登录成功首页
 */

namespace app\admin\controller;

use \think\Request;
use \think\Db;
use \think\Session;
use \think\View;

class FrameController
{
    private $request;
    private $config_info;
    
    //初始化
    public function __construct()
    {
    }

    //首页
    public function index()
    {
        echo 9999;
        //dump($GLOBALS['sys_menu'][0]);die;
        View::share('menu_list', $GLOBALS['sys_menu']);       
        View::share('user_info', Session::get('user_info'));
        return view();
    }

    //欢迎页
    public function wellcome()
    {
        return view();
    }
}
