<?php
//用于定义数据相关的自动验证和自动完成和数据存取接口

namespace app\admin\model;

use think\Model;

class UserModel extends Model
{
    //设置数据表
    protected $table = 'tp_users';

    public  $userId;

    const PRIMARY_KEY = 'user_id';

    /**
     * 获取用户信息
     * @author wuzeguo<1723877247@qq.com>
     * @param int $user_id 用户id
     * @param string $fields 要获取的字段名
     * @return array 用户基本信息
     * @todo 根据where查询条件查找用户表中的相关数据并返回
     */
    public function getUserInfo($where, $fields = '')
    {
		return $this->field($fields)->where($where)->find();
    }

    /**
     * 修改用户信息
     * @author wuzeguo<1723877247@qq.com>
     * @param array $arr 用户信息数组
     * @return boolean 操作结果
     * @todo 修改用户信息
     */
    public function editUserInfo($arr)
    {
        if (!$this->userId) {
            return false;
        } 

        return $this->where('user_id = ' . $this->userId)->update($arr);
    }

    /**
     * 添加用户
     * @author wuzeguo<1723877247@qq.com>
     * @param array $arr 用户信息数组
     * @return boolean 操作结果
     * @todo 添加用户
     */
    public function addUser($arr)
    {
        if (!is_array($arr)) return false;

        $this->data($arr);
        $this->allowField(ture)->save();
        return $this->PRIMARY_KEY;
    }

    /**
     * 删除用户
     * @author wuzeguo<1723877247@qq.com>
     * @param int $user_id 用户ID
     * @return boolean 操作结果
     * @todo is_del设为1
     */
    public function delUser($user_id)
    {
        if (!is_numeric($user_id)) return false;
		return $this->where('user_id = ' . $user_id)->delete();
    }

    /**
     * 根据where子句获取用户数量
     * @author wuzeguo<1723877247@qq.com>
     * @param string|array $where where子句
     * @return int 满足条件的用户数量
     * @todo 根据where子句获取用户数量
     */
    public function getUserNum($where = '')
    {
        return $this->where($where)->count();
    }

    /**
     * 根据where子句查询用户信息
     * @author wuzeguo<1723877247@qq.com>
     * @param string $fields
     * @param string $where
     * @param string $orderby
     * @param string $limit
     * @return array 用户基本信息
     * @todo 根据SQL查询字句查询用户信息
     */
    public function getUserList($fields = '', $where = '', $orderby = '', $limit = null)
    {
        return $this->field($fields)->where($where)->order($orderby)->limit($limit)->select();
    }
}
