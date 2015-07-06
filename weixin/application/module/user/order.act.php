<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 我的订单
 * http://host/weixin/test.php/user/order
 */
class UserOrder extends Action
{
    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_order()
    {
        $order_rows = $this->db->where('user_id', $this->user_id)->result('order');
        $this->view->assign('order_rows', $order_rows);
        $this->view->display('user/order.html');
    }

}
