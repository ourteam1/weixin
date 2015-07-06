<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置手机验证码
 * http://host/weixin/test.php/user/setting
 */
class UserSet_checkcode extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_set_checkcode()
    {
        // $checkcode = ?;//从手机短信接口获取
        session_start();
        $_SESSION['checkcode'] = $checkcode;
        return true;
    }

}
