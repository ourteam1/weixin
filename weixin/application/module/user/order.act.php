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

    public function on_order($status = '')
    {
        $status_text = array('no', '待确认', '待发货', '已收货', '待收货');

        //订单
        if ($status) {
            $this->db->where('status', intval($status));
        }

        $orders = $this->db->where('user_id', $this->user_id)->order_by('create_time', 'desc')->result('order');
        foreach ($orders as $key => $i) {
            $i['status_text'] = $status_text[$i['status']];
            $i['order_info']  = $this->db->where('order_id', $i['order_id'])->result('order_info');
            $i['total_price'] = 0; //总价
            //查询并合并订单的商品信息
            foreach ($i['order_info'] as $key2 => $j) {
                $goods                  = $this->db->where('goods_id', $j['goods_id'])->row('goods');
                $goods['thumb_url']     = IMAGED . $goods['thumb'];
                $i['order_info'][$key2] = array_merge($goods, $j);
                $i['total_price'] += $j['goods_price'];
            }
            $i['order_action'] = $this->db->where('order_id', $i['order_id'])->result('order_action');
            $orders[$key]      = $i; //合并到每个订单
        }

        $this->view->assign('order_rows', $orders);
        $this->view->display('user/order.html');
    }

}
