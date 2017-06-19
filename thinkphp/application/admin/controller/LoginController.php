<?php

/**
 * 后台登录页
 */

namespace app\admin\controller;

use \think\Controller;
use \think\Request;
use \think\Db;
use \think\Session;
use \app\model\UserModel;

class LoginController extends AcpController
{
    //初始化
    public function _initialize()
    {
        //parent::_initialize();
    }

    //登录页
    public function index() 
    {
        return $this->fetch();
    }

    //提交登录页
    //登录成功后跳转到欢迎页
    public function user_login(Request $request)
    {
        $param = $request->post();

        if ($param['loginName'] && $param['password']) {

            $where = 'username = "' . $param['loginName'] . '"';
            $field = 'user_id, username, role_type, password, is_enable, login_try_times, block_time, group_id, realname';
            $user_obj = new UserModel();
            $user = $user_obj->getUserInfo($where, $field);

            if (!$user) return -1; //不存在
            if ($user['is_enable'] == 2) return -3; //用户被删除

            $cur_time = time();
            if ($user['login_try_times'] > 5 && $user['block_time'] > $cur_time) {
                return -4; //使用超过5次
            } else {
				if ($user['password'] != md5(trim($param['password']))) {
					$u_arr = array('login_try_times' => ($user['login_try_times'] + 1));
					if ($user['login_try_times'] >= 5) {
						$u_arr['block_time'] = $cur_time + 1800;
					}
                    $user_obj->userId = $user['user_id'];
                    $user_obj->editUserInfo($u_arr);
                    return -2;
				} else {
					$u_arr = array('login_try_times' => 0, 'block_time' => 0);
                    $user_obj->user_id = $user['user_id'];
                    $user_obj->editUserInfo($u_arr);
					
                    Session::set('user_info', $user);
                    Session::set('user_id', $user['user_id']);

					if ($user['role_type'] == 2)
					{
						//将购物车内商品根据COOKIE值关联当前登录用户
						#$cart_model = new AgentShoppingCartModel();
						#$cart_model->updateShoppingCart();
					}

                    $my_user_type = $user['role_type'];

                    if ($my_user_type == 1) {
                        #$SMSModel = new SMSModel();
                        #$SMSModel->sendAdminLogin($user['username']);	//管理员登录短信提醒

                        return 0;
                    }

                    return -2;
                }
            } 

            return -2;
        }
    }

    //退出登录
    public function login_out()
    {
        Session::clear();

        $url = \think\Url::build(ADMIN_MODULE. '/Login/index');

        return $this->redirect($url);
    }
}
