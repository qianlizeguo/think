<?php

//后台共用库
use think\Hook;

//注册行为
//验证用户是否登录
Hook::add('user_login', 'app\\admin\\behavior\\CheckLogin');
//Hook::exec('app\\admin\\behavior\\CheckLogin','run');
//获取系统设置
Hook::add('action_begin', 'app\\admin\\behavior\\GetSystemConfig');
//获取框架内容
Hook::add('action_begin', 'app\\admin\\behavior\\GetSysMenu');


