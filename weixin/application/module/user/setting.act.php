<?php
if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置
 * http://host/weixin/test.php/user/setting
 */
class UserSetting extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_setting()
    {
        $this->view->display('user/setting.html');
    }

}
