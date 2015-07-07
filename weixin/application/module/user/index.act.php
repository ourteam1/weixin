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

        $date = date('Y-m-d H:i:s');
        $rows = $this->db->select('user_id')->where('user_id', $this->user_id)->where('start_time', '<=', $date)->where('end_time', '>=', $date)->result('favorable');
        $this->view->assign('favorable_count', count($rows));

        $this->view->display('user/index.html');
    }

}
