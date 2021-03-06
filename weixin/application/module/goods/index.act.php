<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 商品列表
 * http://host/weixin/test.php/goods
 */
class GoodsIndex extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_index($category_id = 1, $goods_id = 0)
    {

        // 商品分类信息
        $category = $this->db->where('status', 1)->order_by('location', 'asc')->result('category');
        $this->view->assign('category', json_encode($category));

        // 商品信息
        $goods_rows = $this->db->where('status', 1)->result('goods');
        $goods      = array();
        foreach ($goods_rows as $k => $i) {
            $i['image']      = IMAGED . $i['image'];
            $i['thumb']      = IMAGED . $i['thumb'];
            $i['goods_name'] = mb_substr($i['goods_name'], 0, 10, 'utf-8');
            //先删除再添加
            $goods[$i['goods_id']] = $i;
        }
        unset($goods_rows);
        // echo "<pre>";
        // var_dump($goods);exit;
        $this->view->assign('goods', json_encode($goods));

        // 收货人信息
        $order_info = $this->db->where('user_id', $this->user_id)->order_by('create_time', 'desc')->row('order');
        $consignee  = array(
            'username'       => isset($order_info['username']) ? $order_info['username'] : '', // 收货人姓名
            'mobile'         => isset($order_info['mobile']) ? $order_info['mobile'] : '', // 收货人联系方式
            'city'           => isset($order_info['city']) ? $order_info['city'] : '', // 收货人所在城市
            'area'           => isset($order_info['area']) ? $order_info['area'] : '', // 收货人所在地区
            'address'        => isset($order_info['address']) ? $order_info['address'] : '', // 收货人详细地址
            'delivery_times' => isset($order_info['delivery_times']) ? $order_info['delivery_times'] : '', // 配送时间
            'payment_model'  => isset($order_info['payment_model']) ? $order_info['payment_model'] : '', // 支付方式
            'invoice'        => isset($order_info['invoice']) ? $order_info['invoice'] : '', // 发票抬头
        );
        $this->view->assign('consignee', json_encode($consignee));

        // 用户信息
        $userinfo = $this->db->where('user_id', $this->user_id)->row('user');
        $user     = array(
            'user_id'  => isset($userinfo['user_id']) ? $userinfo['user_id'] : '',
            'mobile'   => isset($userinfo['mobile']) ? $userinfo['mobile'] : '',
            'email'    => isset($userinfo['email']) ? $userinfo['email'] : '',
            'amount'   => isset($userinfo['amount']) ? $userinfo['amount'] : '',
            'score'    => isset($userinfo['score']) ? $userinfo['score'] : '',
            'nickname' => isset($userinfo['nickname']) ? $userinfo['nickname'] : '',
            'sex'      => isset($userinfo['sex']) ? $userinfo['sex'] : '',
        );
        $this->view->assign('user', json_encode($user));
        $this->view->assign('category_id', $category_id);
        $this->view->assign('goods_id', $goods_id);

        $this->view->display('goods/goods_index.html');
    }

}
