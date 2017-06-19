<?php
/**
 * 控制器类基类
 * 继承Controller类
 * 后台共用
 */
namespace app\admin\controller;

use \think\Controller;
use \think\Request;
use \think\Db;
use \think\Session;

class GlobalController extends Controller
{
    protected $parameter;
    protected $cur_url;
    protected $module;
    protected $controller;
    protected $action;

    //初始化
    public function _initialize()
    {
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
        $system_config['MODULE_NAME'] = $request->module();
        $system_config['CONTROLLER_NAME'] = $request->controller();
        $system_config['ACTION_NAME'] = $request->action();
        $GLOBALS['system_config'] = $system_config; 

        //过滤传入参数
        $_REQUEST = array_merge($_GET,$_POST);
        $this->mapi_filter_request($_REQUEST);

        $this->parameter = $_REQUEST;
        $this->cur_url = url('', $this->parameter);
        $this->assign("cur_url", $this->cur_url);

        $this->module = $system_config['MODULE_NAME'];
        $this->controller = $system_config['CONTROLLER_NAME'];
        $this->action = $system_config['ACTION_NAME'];

		//种COOKIE
		if (isset($_COOKIE['user_cookie'])) {
			$GLOBALS['user_cookie'] = $_COOKIE['user_cookie'];

		} else {
			$cookie_value = md5($request->ip() . time());
            Cookie::set('user_cookie',null);    //一定要先清空
            Cookie::set('user_cookie', $cookie_value, time()+3600*24*30);	//默认30天有效期
			$GLOBALS['user_cookie'] = $cookie_value;
		}

        //版本号
        $version = C("VERSION");
        $version = $version ? $version : 2016031703;	
		$this->assign('version', $version);
    }


    //过滤请求
    function mapi_filter_request(&$request)
    {
        foreach($request as $k=>$v)
        {
            if(is_array($v))
            {
                mapi_filter_request($request[$k]);
            }
            else
            {
                $request[$k] = stripslashes(trim($v));
            }
        }

    }
}
