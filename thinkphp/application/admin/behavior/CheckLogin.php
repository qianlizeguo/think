<?php
namespace app\admin\behavior;

use \think\Request;
use \think\Session;

//php版本大于5.4则无需引入
\think\Loader::import('controller/Jump', TRAIT_PATH, EXT);

//行为类
class CheckLogin
{
    use \traits\controller\Jump;

    public function run()
    {
        $request = Request::instance();
        $dispatch = $request->dispatch()['module'];
        $controller = $dispatch[1] ? $dispatch[1] : \think\Config::get('default_controller');
        $action = $dispatch[2] ? $dispatch[2] : \think\Config::get('default_action');

        $admin_module = \think\Config::get('admin_module');
        if ($controller != 'Login' && $action != 'index') {
            if (!intval(Session::get('user_id'))) {
                $url = \think\Url::build($admin_module . '/Login/index');
                $this->redirect($url);
            }
        }
    }
}
