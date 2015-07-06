<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 会员中心
 * http://host/weixin/test.php/user/index
 */
class UserIndex extends Action
{
    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_index()
    {
        $user_row = $this->db->where('user_id', $this->user_id)->row('user');
        if (!$user_row) {
            $this->error_message('非法访问');
        }

        foreach ($user_row as $key => $value) {
            $this->view->assign($key, $value);
        }
        $this->view->display('user/index.html');
    }

}
