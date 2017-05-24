<?php
namespace app\admin\behavior;

use \think\Db;
use \think\Request;
use \think\Session;

/**
 * 获取系统配置
 */
class GetSysMenu
{
    public function run()
    {
        $request = Request::instance();

        //获取左边框
        include_once(APP_PATH . '/admin/sys_menu.php');
        $GLOBALS['sys_menu'] = $admin_menu_file;
    }

    public function checkUserPriv()
    {
        $user_id = intval(Session::get('user_id'));
    }
}
