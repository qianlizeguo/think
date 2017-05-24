<?php

//后台共用库
use think\Hook;

//注册行为
//验证用户是否登录
Hook::add('module_init', 'app\\admin\\behavior\\CheckLogin');
//获取系统设置
Hook::add('action_begin', 'app\\admin\\behavior\\GetSystemConfig');
//获取框架内容
Hook::add('action_begin', 'app\\admin\\behavior\\GetSysMenu');


