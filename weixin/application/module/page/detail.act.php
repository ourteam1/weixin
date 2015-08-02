<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 订单列表
 * http://host/weixin/test.php/order
 */
class PageDetail extends Action
{

    public function __construct()
    {
        parent::__construct();

        $this->wxauth();
    }

    public function on_detail($page_id = null)
    {
        // 公司信息介绍列表
        $page = $this->db->where('page_id', $page_id)->row('pages');
        $this->view->assign('page', $page);
        $this->view->display('page/detail.html');
    }

}
