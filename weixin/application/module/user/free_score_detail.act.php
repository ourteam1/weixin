<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置
 * http://host/weixin/test.php/user/free_score_detail
 */
class UserFree_score_detail extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_free_score_detail()
    {
        $favorable_id = get_post('id');
        if (!$favorable_id) {
            $this->error_message('缺少参数');
        }

        $date      = date('Y-m-d H:i:s');
        $favorable = $this->db->where('user_id', $this->user_id)->where('favorable_id', $favorable_id)->where('start_time', '<=', $date)->where('end_time', '>=', $date)->row('favorable');
        foreach ($favorable as $key => $value) {
            $this->view->assign($key, $value);
        }
        $this->view->display('user/free_score_detail.html');
    }

}
