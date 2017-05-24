<?php
/**
 * 后台登录成功首页
 */

namespace app\admin\controller;

use \think\Request;
use \think\Db;
use \think\Session;

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
        return view();
    }

    //欢迎页
    public function wellcome()
    {
        return view();
    }
}
