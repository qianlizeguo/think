<?php
/**
 * 后台登录成功首页
 */

namespace app\admin\controller;

use \think\Controller;
use \think\Request;
use \think\Db;
use \think\Session;
use \think\View;

class FrameController extends AcpController
{
    //初始化
    public function _initialize()
    {
        parent::_initialize();
    }

    //首页
    public function index()
    {
        //获取框架内容
        //$sys_menu = \think\Hook::exec('app\\admin\\behavior\\GetSysMenu', 'run');

        $this->assign('menu_list', $sys_menu);       
        $this->assign('user_info', Session::get('user_info'));
        return $this->fetch();
    }

    //欢迎页
    public function wellcome()
    {
        return $this->fetch();
    }
}
