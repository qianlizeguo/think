<?php

//后台共用函数库
use think\Db;
use think\Request;

/**
 * 获取系统配置
 */
function get_system_config() 
{
    $config = Db::name('config')->cache(true, 86400)->select();

    if (!$config->isEmpty()) {
        $system_config = [];

        foreach ($config as $k => $v) {
            $system_config[strtoupper($v['config_name'])] = html_entity_decode($v['config_value']);   
        }
    }

    $system_config['CUR_TIME'] = time();
    $system_config['WEBSITE_DOMAIN'] = Request::instance()->domain();
    $system_config['IP'] = Request::instance()->ip();

    $GLOBALS['system_config'] = $system_config; 

    return $system_config;
}
