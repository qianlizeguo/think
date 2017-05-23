<?php
namespace app\admin\model;

use think\Model;
use think\Config;

class User extends Model
{
    //设置数据表
    protected $table = 'tp_users';

    //检测用户名密码是否正确
    public function getUserInfo($where) 
    {
        return $this->where($where)->find();
    }
}
