<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置
 * http://host/weixin/test.php/user/favorable
 */
class UserFavorable extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_favorable()
    {
        $date       = date('Y-m-d H:i:s');
        $favorables = $this->db->where('user_id', $this->user_id)->where('start_time', '<=', $date)->where('end_time', '>=', $date)->result('favorable');

        $this->view->assign('favorables', $favorables);
        $this->view->display('user/favorable.html');
    }

}
