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

    public function run($param)
    {
        dump($param);
        echo 8888;
        $request = Request::instance();
        $admin_module = \think\Config::get('admin_module');
        if (!$request->isAjax()) {
            if ($request->controller() != 'Login' && $request->action() != 'index') {
                if (!intval(Session::get('user_id'))) {
                    $url = \think\Url::build($admin_module . '/Login/index');
                    //$this->redirect($url);
                }
            }
        }
    }
}
