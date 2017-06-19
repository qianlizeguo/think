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
        //$request = Request::instance();

		//取得当前用户组的所有权限菜单
        $my_menu_file = [];
        foreach  ($admin_menu_file AS $k => $v) {
        }
        return $admin_menu_file;
    }

	/*
	* 获取当前用户的所有权限
	*/
    private function get_all_my_priv() 
    {
        include_once APP_PATH . '/admin/sys_menu.php';

		if ($this->login_user['group_id'] == 1) { //超级管理员有所有权限
			return $admin_menu_file;
		}

		$my_menu_file = array();

		$i = 0;
		foreach ($admin_menu_file as $k => $v) { //$v = array('id' => 'XX', 'name' => 'XX', 'mod_do_url' => '', 'in_menu' => '', 0=>array(),1=>Array()...);
			if (!is_array($v)) {
				continue;
			}

			if (in_array($v['id'], $this->login_user['priv_str_arr'])) {
				$my_menu_file[$i] = array('id' => $v['id'], 'name' => $v['name'], 'mod_do_url' => $v['mod_do_url'], 'in_menu' => $v['in_menu'], 'mod_name' => $v['mod_name']);
			} else {
				continue;
			}

			foreach ($v as $m => $n) { //有效的（即是数组的值）项$n = array('id' => 'XX', 'name' => 'XX', 'mod_do_url' => '/XX-acp_XX', 'in_menu' => '');
				if (!is_array($n)) {
					continue;
				}

				foreach ($n AS $a => $b)
				{
					$p = 0;
					foreach ($b AS $c => $d)
					{
						if (in_array($d['id'], $this->login_user['priv_str_arr'])) {
							$my_menu_file[$i][$m][$a][$p] = array('id' => $d['id'], 'name' => $d['name'], 'mod_do_url' => $d['mod_do_url'], 'in_menu' => $d['in_menu']);
							$p++;
						}
					}
				}
			}

			$i++;
		}

		return $my_menu_file;
	}

}
