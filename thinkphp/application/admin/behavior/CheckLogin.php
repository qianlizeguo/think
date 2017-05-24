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

    public function run(Request $request)
    {
        $admin_module = \think\Config::get('admin_module');
        if (!$request->isAjax()) {
            if ($request->module() != $admin_module && $request->controller() != 'Login' && $request->action() != 'index') {
                if (!intval(Session::get('user_id'))) {
                    $url = \think\Url::build($admin_module . '/Login/index');
                    $this->redirect($url);
                }
            }
        }
    }
}
