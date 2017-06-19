<?php
/**
 * 后台共公文件，后台所有控制器继承基类
 */

namespace app\admin\controller;

use \think\Controller;
use \think\Request;
use \think\Db;
use \think\Session;
use \think\View;

class AcpController extends GlobalController
{
    //初始化
    public function _initialize()
    {
        parent::_initialize();

		//取得登录用户信息
		$user_id = Session::get('user_info')['user_id'];
		if ($user_id) {
            Session::set('user_id', $user_id);
			$Users = Db::name("users");
			$user_infom = $Users->alias('u')->join(C('prefix') . 'users_group g',  'u.group_id = g.group_id')->where('u.user_id = ' . $user_id)->select();
			$user_info = $user_infom[0];
			$user_info['priv_str_arr'] = explode(',', $user_info['priv_str']);
			//print_r($user_info);
			$this->login_user = $user_info;

			$this->assign("login_user", $user_info);
			$this->assign("login_info", $user_info);	//用login_info保持front和ucp模板输出变量一致 lyf 20140423

			unset($user_info['priv_str']);
			unset($user_info['manage_group_id_list']);
			unset($user_info['priv_str_arr']);
			unset($user_info['group_name']);
			unset($user_info['user_type']);
			$GLOBALS['user_info'] = $user_info;

		} else {

			$this->login_user = $user_info = 0;
			$this->assign("login_user", $user_info);
			$this->assign("login_info", $user_info);
            Session::set('user_info', null);
		}

		if (!$this->login_user) $this->redirect(url(ADMIN_MODULE.'/Login/index/redirect/' . $this->cur_url)); //没登录先进登录页面
		if ($this->login_user['role_type'] != 1) $this->redirect(url(ADMIN_MODULE.'/Login/login_out')); //已登录但不是管理员，则强制退出要求重新登录

		//取得当前用户组的所有权限菜单
		#$this->my_menu_file = S('group_priv_' . $this->login_user['group_id']);
		$this->my_menu_file = false;

		if (!$this->my_menu_file) {
			$this->my_menu_file = $this->get_all_my_priv();
		}

		$this->cur_priv_id = ''; //当前页面的权限id值
		$this->cur_priv_upper_id = ''; //上级权限id值
		$this->cur_priv_in_menu = ''; //in_menu中的值

		#echo "<pre>";
		#print_r($this->my_menu_file);
		#echo "</pre>";
		//验证权限（首页和部分特殊页面在不用验证）
		if (!in_array($this->action, array('set_menu', 'uploadHandler', 'delImage', 'common_article')) && $user_id != 1)
		{
            if (!$this->checkpriv('/' . MODULE_NAME . '/' . ACTION_NAME, true))
			{
				redirect(U('/acp'), 5, '您没有访问此操作的权限，请联系系统管理员！');
			}
		}

		/*获取左侧菜单列表begin*/
		$mod_id = $this->_get('mod_id');
		$mod_id = ($mod_id == '') ? $this->get_mod_id() : intval($mod_id);
		$mod_id = !is_int($mod_id) ? $this->get_mod_id() : $mod_id;
		//防止地址栏恶意乱填，默认选中索引为1(订单)的一级菜单
		$mod_id = ($mod_id >= count($this->my_menu_file)) ? 2 : $mod_id;
		#$mod_id = 'acp' == strtolower(MODULE_NAME) ? 2 : $mod_id;

		$my_menu_list = $this->my_menu_file[$mod_id]['menu_list'];

		$menu_no = $this->get_in_menu($mod_id, ACTION_NAME, MODULE_NAME);

		$this->assign("menu_no", $menu_no);
		$this->assign("mod_id", $mod_id);
		$this->assign("my_menu_list", $my_menu_list);
		/*获取左侧菜单列表end*/

		#echo "<pre>";
		#print_r($my_menu_file);
		#echo "</pre>";
		$this->assign("menu_file", $this->my_menu_file);

		$this->assign('cur_priv_id', $this->cur_priv_id);
		$this->assign('cur_priv_upper_id', $this->cur_priv_upper_id);
		$this->assign('cur_priv_in_menu', $this->cur_priv_in_menu);

		//页面布局和导航固定模式 的cookie值传递
		$this->assign('ui_layoutMod', $_COOKIE['ui_layoutMod']);
		$this->assign('ui_navPosMod', $_COOKIE['ui_navPosMod']);
    }


	/*
	* 获取当前用户的所有权限
	*/
	protected function get_all_my_priv() {
		include_once(APP_PATH . '/admin/sys_menu.php');

		if ($this->login_user['group_id'] == 1) { //超级管理员有所有权限
			return $admin_menu_file;
		}

        //dump($admin_menu_file);die;
		$my_menu_file = array();

		$i = 0;
		foreach ($admin_menu_file as $k => $v) {
			//格式如下  $v = 'id' => XX,  'mod_name' => XX,  'name' => XX,  'mod_do_url' => XX,  'in_menu' => XX,  'default_url' => '', 'menu_list' => array ( 0 => array()  ...  1 => array () ...)
			//主要是检查menu_list 中的数据， 如果有数据，则加上父类信息

			if (!is_array($v)) {
				continue;
			}

			if (in_array($v['id'], $this->login_user['priv_str_arr'])) {
				$my_menu_file[$i] = array('id' => $v['id'], 'name' => $v['name'], 'mod_do_url' => $v['mod_do_url'], 'in_menu' => $v['in_menu'], 'mod_name' => $v['mod_name']);
			} else {
				continue;
			}

			foreach ($v['menu_list'] as $m => $n) { //有效的（即是数组的值）项$n = array('id' => 'XX', 'name' => 'XX', 'mod_do_url' => '/XX-acp_XX', 'in_menu' => '');
				if (!is_array($n)) {
					continue;
				}
                //如果存在in_menu则不显示
                if (in_array($n['id'], $this->login_user['priv_str_arr'])) {
                        $my_menu_file[$i]['menu_list'][] = array('id' => $n['id'], 'name' => $n['name'], 'mod_do_url' => $n['mod_do_url'], 'in_menu' => $n['in_menu']);

                }
			}

			$i++;
		}

		return $my_menu_file;
	}

	/*
	* 验证ajax权限
	*/
	protected function check_ajax_priv()
	{
		require('Conf/acp_ajax_priv.php');
		foreach ($admin_ajax_file AS $k => $v)
		{
			foreach ($v AS $key => $value)
			{
				if (strtolower(MODULE_NAME) == strtolower($value['mod']) && strtolower(ACTION_NAME) == strtolower($value['do']))
				{
					return true;
				}
			}
		}

		return false;
	}

	/*
	* 验证权限
	*/
	protected function checkpriv($mod_do_url, $nz_curpage = false) {
		$have_priv = false;
		$have_find = false;

		foreach ($this->my_menu_file as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $m => $n) {
					foreach ($n AS $a => $b)
					{
						foreach ($b AS $c => $d)
						{
							if (is_array($d) && strtolower($d['mod_do_url']) == strtolower($mod_do_url)) {
								if (in_array($d['id'], $this->login_user['priv_str_arr'])) {
									$have_priv = true;
								}

								$have_find = true;

								if ($nz_curpage) {
									$this->cur_priv_id = $d['id'];
									$this->cur_priv_upper_id = $v['id'];
									$this->cur_priv_in_menu = ($d['in_menu']) ? $d['in_menu'] : $d['id'];
								}
								break;
							}
						}
					}
				}
			}
			if ($have_find) {
				break;
			}
		}
		return $have_priv;
	}
}
