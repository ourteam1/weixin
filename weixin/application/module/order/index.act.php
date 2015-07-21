<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 订单列表
 * http://host/weixin/test.php/order
 */
class OrderIndex extends Action
{

    public function __construct()
    {
        parent::__construct();

        $this->wxauth();
    }

    public function on_index($order_sn = '')
    {
        $property_data = array();
        $property      = $this->db->where('name', 'like', 'order.action.%')->result('property');
        foreach ($property as $i) {
            $property_data[$i['value']] = $i['label'];
        }

        // 订单列表
        $order = $this->db->where('user_id', $this->user_id)->order_by('create_time', 'desc')->result('order');
        foreach ($order as $k => $i) {
            $i['status_text'] = $property_data[$i['status']];
            $i['order_info']  = $this->db->where('order_id', $i['order_id'])->result('order_info');
            foreach ($i['order_info'] as $k2 => $j) {
                $goods_info              = $this->db->where('goods_id', $j['goods_id'])->row('goods');
                $goods_info['thumb_url'] = IMAGED . $goods_info['thumb'];
                $i['order_info'][$k2]    = array_merge($goods_info, $j);
            }
            $i['order_action'] = $this->db->where('order_id', $i['order_id'])->result('order_action');
            $order[$k]         = $i;
        }
        $this->view->assign('order', json_encode($order));

        $this->view->assign('order_sn', $order_sn);

        $this->view->display('order/order_index.html');
    }

}
