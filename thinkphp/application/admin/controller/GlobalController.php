<?php
/**
 * 控制器类基类
 * 继承Controller类
 */
namespace app\admin\controller;

use \think\Controller;
use \think\Request;
use \think\Db;
use \think\Session;

class GlobalController extends Controller
{
    //初始化
    public function _initialize()
    {
        if (!intval(Session::get('user_id'))) {
            $url = \think\Url::build(ADMIN_MODULE . '/Login/index');
            $this->redirect($url);
        }

        $config = Db::name('config')->cache(true, 86400)->select();

        if (!$config->isEmpty()) {
            $system_config = [];

            foreach ($config as $k => $v) {
                $system_config[strtoupper($v['config_name'])] = html_entity_decode($v['config_value']);   
            }
        }

        $request = Request::instance();
        $system_config['CUR_TIME'] = time();
        $system_config['WEBSITE_DOMAIN'] = $request->domain();
        $system_config['IP'] = $request->ip();
        $GLOBALS['system_config'] = $system_config; 
    }
}
