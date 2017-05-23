<?php
/**
 * 后台登录成功首页
 */

namespace app\admin\controller;

use \think\Request;
use \think\Db;

class Frame
{
    private $request;
    private $config_info;
    
    //初始化
    public function __construct()
    {
        $this->request = Request::instance();
        $this->config_info = get_system_config();
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
