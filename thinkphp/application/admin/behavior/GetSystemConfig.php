<?php
namespace app\admin\behavior;

use \think\Db;
use \think\Request;

/**
 * 获取系统配置
 */
class GetSystemConfig 
{
    public function run()
    {
        $request = Request::instance();
        if (!$request->isAjax()) {

            $admin_module = \think\Config::get('admin_module');
            $config = Db::name('config')->cache(true, 86400)->select();

            if (!$config->isEmpty()) {
                $system_config = [];

                foreach ($config as $k => $v) {
                    $system_config[strtoupper($v['config_name'])] = html_entity_decode($v['config_value']);   
                }
            }

            $system_config['CUR_TIME'] = time();
            $system_config['WEBSITE_DOMAIN'] = $request->domain();
            $system_config['IP'] = $request->ip();
            $GLOBALS['system_config'] = $system_config; 

        }
    }
}
