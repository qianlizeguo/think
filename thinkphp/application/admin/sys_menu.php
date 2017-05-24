<?php
/**
 * 管理员权限数组
 * 
 * id 为权限唯一标识符
 * name 为权限或菜单对应的名字
 * mod_do_url 为对应的网址，其中do必须以acp_开头
 * in_menu 当此为空时表示本身就是左侧菜单；不为空时，其值必须是id，此id就是当' . C('USER_NAME'] . '处于此权限页面时对应左侧菜单的特殊显示的权限id
 * 
 * ID规则：第一级为01至99的数字；
 *        第二级为0101至9999的数字，其中前两位为上级ID值；
 *        第三级为010101至999999的数字，其中最前面两位为顶级ID值，中间两位为上级ID值；
 *        以此类推
 */
$admin_menu_file = [];
$menu_id = '0';
$mod_id = '00';

$admin_menu_file[$menu_id] = ['id' => ++$mod_id, 'mod_name' => 'System', 'name' => '系统管理', 'mod_do_url' => '', 'in_menu' => '', 'default_url' => '/AcpConfig/base_config'];
$admin_menu_file[$menu_id++]['menu_list'] = [

    ['id' => $mod_id.'01', 'name' => '欢迎页面', 'mod_do_url' => '/Acp/index', 'in_menu' => ''],			//OK-CC
    ['id' => $mod_id.'02', 'name' => '基础设置', 'mod_do_url' => '/AcpConfig/base_config', 'in_menu' => ''],			//OK-CC
    ['id' => $mod_id.'03', 'name' => '新用户关注设置', 'mod_do_url' => '/AcpConfig/subscribe_set', 'in_menu' => ''],
    ['id' => $mod_id.'04', 'name' => '支付方式设置', 'mod_do_url' => '/AcpPayment/list_payment', 'in_menu' => ''],	//OK-DONE
    ['id' => $mod_id.'05', 'name' => '微信支付设置', 'mod_do_url' => '/AcpPayment/set_wxpay', 'in_menu' => $mod_id.'04'],//OK-DONE
    ['id' => $mod_id.'06', 'name' => '运费设置', 'mod_do_url' => '/AcpConfig/shopping_fare', 'in_menu' => ''],

];

$admin_menu_file[$menu_id] = ['id' => ++$mod_id, 'mod_name' => 'System', 'name' => '管理员与权限管理', 'mod_do_url' => '', 'in_menu' => '', 'default_url' => '/AcpConfig/base_config'];
$admin_menu_file[$menu_id++]['menu_list'] = [

    ['id' => $mod_id .'06', 'name' => '管理员列表', 'mod_do_url' => '/AcpRole/list_admin', 'in_menu' => ''],
    ['id' => $mod_id . '07', 'name' => '添加管理员', 'mod_do_url' => '/AcpRole/add_admin', 'in_menu' => $mod_id .'06'],
    ['id' => $mod_id . '08', 'name' => '修改管理员', 'mod_do_url' => '/AcpRole/edit_admin', 'in_menu' => $mod_id .'06'],
    ['id' => $mod_id . '09', 'name' => '删除管理员', 'mod_do_url' => '/AcpRole/del_admin', 'in_menu' => $mod_id .'06'],
    ['id' => $mod_id . '10', 'name' => '激活/禁用管理员', 'mod_do_url' => '/AcpRole/set_admin', 'in_menu' => $mod_id.'06'],
    ['id' => $mod_id . '11', 'name' => '恢复已删除管理员', 'mod_do_url' => '/AcpRole/hf_admin', 'in_menu' => $mod_id.'06'],
    ['id' => $mod_id . '12', 'name' => '角色列表', 'mod_do_url' => '/AcpRole/list_role', 'in_menu' => ''],
    ['id' => $mod_id . '13', 'name' => '添加角色', 'mod_do_url' => '/AcpRole/add_role', 'in_menu' => $mod_id.'12'],
    ['id' => $mod_id . '14', 'name' => '修改角色', 'mod_do_url' => '/AcpRole/edit_role', 'in_menu' => $mod_id.'12'],
    ['id' => $mod_id . '15', 'name' => '删除角色', 'mod_do_url' => '/AcpRole/del_role', 'in_menu' => $mod_id.'12'],
    ['id' => $mod_id . '16', 'name' => '激活/禁用角色', 'mod_do_url' => '/AcpRole/set_admin_group', 'in_menu' => $mod_id.'12'],
    ['id' => $mod_id . '17', 'name' => '恢复已删除角色', 'mod_do_url' => '/AcpRole/hf_admin_group', 'in_menu' => $mod_id.'12'],

];
