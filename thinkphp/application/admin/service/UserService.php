<?php
//服务层，用于定义用户相关的服务接口等
namespace app\admin\service;

use think\Model;
use app\admin\model\User;

class UserService extends Model
{
    public function get_user_info($param) 
    {
        $where = 'username = "' . $param['loginName'] . '"';
        $user_obj = new User();
        return $user_obj->getUserInfo($where);
    }
}
