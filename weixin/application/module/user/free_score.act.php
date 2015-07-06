<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置
 * http://host/weixin/test.php/user/free_score
 */
class UserFree_score extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_free_score()
    {
        $rows = $this->db->where('user_id', $this->user_id)->result('free_get_score');

        //读取oracle中优惠券信息
        if ($rows) {
            foreach ($rows as $row) {
                $codes[] = $row['code'];
                //hdbh?
                $url     = 'http://42.62.73.239:8080/CloudServer/wi.do?method=GetDiscountCode_DX&hdbh=' . $hdbh;
                $yhq_row = $this->http->get($url);
            }
        }

        $this->view->assign('rows', $rows);
        $this->view->display('user/free_score.html');
    }

}
