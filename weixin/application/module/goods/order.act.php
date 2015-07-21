<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 完善收货人信息，并提交订单
 * http://host/weixin/index.php/goods/order
 */
class GoodsOrder extends Action
{

    // 定义是否支付
    public $is_pay = 0;

    public function __construct()
    {
        parent::__construct();

        $this->wxauth();
    }

    public function on_order()
    {
        $order = isset($_REQUEST['Order']) ? $_REQUEST['Order'] : array();

        // 启动事务
        $this->db->trans_start();

        // 查询购物车信息
        $cart = $this->_get_cart_info();

        // 用户信息
        $userinfo = $this->_get_user_info();

        // 计算总金额
        $order['amount'] = $this->_get_total_price($cart);

        // 计算总金币
        $order['score'] = $this->_get_total_score($cart);

        // 生成唯一订单编号
        $order['order_sn'] = date('YmdHis') . mt_rand(99, 999);

        // 检测支付方式
        if ($order['amount'] > 0) {
            $order['payment_model'] = $this->_chk_payment($userinfo, $order);
        }

        // 金币兑换
        if ($order['score'] > 0 && !$order['amount']) {
            $this->is_pay           = 1;
            $order['payment_model'] = "金币支付";
        }
        // 添加订单信息
        $order = $this->_add_order($order);

        // 添加订单详情
        $this->_add_order_detail($cart, $order['order_id']);

        // 清空购物车
        $this->_clear_cart();

        // 修改商品下单数
        $this->_update_order_number($cart);

        // 余额支付
        if ($order['payment_model'] == '余额支付') {
            $this->_balance_of_payments($userinfo, $order['amount']);
        }

        // 金币支付
        if ($order['payment_model'] = "金币支付") {
            $this->_balance_of_score($userinfo, $order['score']);
        }

        // 提交事务
        $this->db->trans_commit();

        // 发送客服消息
        $this->send_wx_news($cart, $order);

        // 返回成功信息
        die_json(array('message' => 'ok.', 'order_sn' => $order['order_sn']));
    }

    /**
     * 购物车信息
     */
    public function _get_cart_info()
    {
        $this->db->where('user_id', $this->user_id)->where('session_id', session_id());
        $cart = $this->db->result('cart');
        if (!$cart) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10011, 'error' => '下单失败，购物车为空！'));
        }
        return $cart;
    }

    /**
     * 用户信息
     */
    public function _get_user_info()
    {
        $userinfo = $this->db->where('user_id', $this->user_id)->row('user');
        if (!$userinfo) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10012, 'error' => '用户不存在！'));
        }
        return $userinfo;
    }

    /**
     * 获取总价格
     */
    public function _get_total_price($cart)
    {
        $total_price = 0;
        foreach ($cart as $i) {
            $cart_num    = $i['cart_num'];
            $goods_price = $i['goods_price'];
            $total_price += $goods_price * $cart_num;
        }
        return $total_price;
    }

    /**
     * 获取总金币
     */
    public function _get_total_score($cart)
    {
        $total_score = 0;
        foreach ($cart as $i) {
            $cart_num    = $i['cart_num'];
            $goods_score = $i['goods_score'];
            $total_score += $goods_score * $cart_num;
        }
        return $total_score;
    }

    /**
     * 检测支付方式
     */
    public function _chk_payment($userinfo, $order)
    {
        $payment_property = $this->db->where('name', 'like', 'payment.model.%')->where('value', $order['payment_model'])->row('property');
        if (!$payment_property) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10013, 'error' => '支付方式不正确！'));
        }
        if ($order['payment_model'] == '微信支付') {

            include_once LIBS_DIR . 'wxpay/lib/WxPay.Api.php';
            include_once LIBS_DIR . 'wxpay/unit/WxPay.JsApiPay.php';

            //统一订单的配置
            $subject = $attach = $order['order_sn'];
            $total_fee  = $order['amount'] * 100; //单位分
            $notify_url = site_url('goods/wxpay_notify'); //回调地址
            $date       = date("YmdHis");
            //生成统一订单
            $input = new WxPayUnifiedOrder();
            $input->SetBody($subject);
            $input->SetAttach($attach);
            $input->SetOut_trade_no(WxPayConfig::MCHID . $date);
            $input->SetTotal_fee($total_fee);
            $input->SetTime_start($date);
            $input->SetTime_expire(date("YmdHis", time() + 600)); //付款有效期10分钟
            $input->SetGoods_tag("tag");
            $input->SetNotify_url($notify_url);
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($this->get_openid());
            //由配置生成统一的订单配置
            $order ＝ WxPayApi::unifiedOrder($input); 
            logger("统一的订单配置:order=" . json_encode($order));
            //由订单配置生成js请求的参数
            $tools           = new JsApiPay();
            $jsApiParameters = $tools->GetJsApiParameters($order);

            $this->view->assign('jsApiParameters', $jsApiParameters);
            $this->view->display('goods/unified_order.html');
        }
        if ($order['payment_model'] == '余额支付') {
            if ($userinfo['amount'] < $amount) {
                $this->db->trans_rollback(); // 回滚事务
                die_json(array('error_code' => 10015, 'error' => '余额不足！'));
            }
            $this->is_pay = 1; // 使用余额支付时，如果余额够用就，设置为已经支付
        }
        return $order['payment_model'];
    }

    /**
     * 添加订单信息
     */
    public function _add_order($order)
    {
        $data     = array(
            'user_id'        => $this->user_id,
            'order_sn'       => $order['order_sn'],
            'amount'         => $order['amount'], // 总金额
            'score'          => $order['score'], // 金币
            //            'invoice'         => $order['invoice'], // 发票抬头
            'remark'         => $order['remark'], // 订单备注
            'payment_model'  => $order['payment_model'], // 支付方式
            'delivery_times' => $order['delivery_times'], // 订单配送时间
            'username'       => $order['consignee']['username'], // 收货人姓名
            'mobile'         => $order['consignee']['mobile'], // 收货人联系方式
            'city'           => $order['consignee']['city'], // 收货人所在城市
            'area'           => $order['consignee']['area'], // 收货人所在地区
            'address'        => $order['consignee']['address'], // 收货人详细地址
            'is_pay'         => $this->is_pay, // 未支付
            'status'         => 1, // 订单未确认
            'create_time'    => date('Y-m-d H:i:s'),
            'modify_time'    => date('Y-m-d H:i:s'),
        );
        $res = $this->db->insert('order', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10016, 'error' => '下单失败，订单信息记录失败！'));
        }
        $data['order_id'] = $this->db->last_insert_id();
        return $data;
    }

    /**
     * 添加订单详情信息
     */
    public function _add_order_detail($cart, $order_id)
    {
        $flag = true;
        foreach ($cart as $i) {
            $goods_id    = $i['goods_id'];
            $cart_num    = $i['cart_num'];
            $goods_price = $i['goods_price'];
            $goods_score = $i['goods_score'];
            $data        = array(
                'order_id'    => $order_id,
                'goods_id'    => $goods_id,
                'goods_price' => $goods_price,
                'goods_score' => $goods_score,
                'cart_num'    => $cart_num,
            );
            $res = $this->db->insert('order_info', $data);
            if ($res === false) {
                $flag = false;
                break;
            }
        }
        if (!$flag) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10017, 'error' => '下单失败，订单详情记录失败！'));
        }
        return true;
    }

    /**
     * 清空购物车
     */
    public function _clear_cart()
    {
        $this->db->where('user_id', $this->user_id)->where('session_id', session_id());
        $res = $this->db->delete('cart');
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10018, 'error' => '下单失败，购物车清空失败！'));
        }
        return true;
    }

    /**
     * 修改商品下单数
     */
    public function _update_order_number($cart)
    {
        $flag = true;
        foreach ($cart as $i) {
            $goods_id   = $i['goods_id'];
            $goods_info = $this->db->where('goods_id', $goods_id)->row('goods');
            if (!$goods_info) {
                continue;
            }
            $cart_num = $i['cart_num'];
            $data     = array(
                'order_number' => $goods_info['order_number'] + $cart_num,
                'modify_time'  => date('Y-m-d H:i:s'),
            );
            $this->db->where('goods_id', $goods_id)->update('goods', $data);
        }
        return true;
    }

    /**
     * 余额支付
     */
    public function _balance_of_payments($userinfo, $amount)
    {
        if ($userinfo['amount'] < $amount) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10019, 'error' => '余额不足！'));
        }

        // 扣除余额
        $data = array(
            'amount'      => $userinfo['amount'] - $amount,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $this->user_id)->update('user', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10020, 'error' => '下单失败，余额支付失败！'));
        }

        // 如果是余额支付 - 记录账户变动记录
        $data = array(
            'user_id'     => $this->user_id,
            'action'      => 'user.order_pay',
            'action_name' => '订单支付￥' . $amount . '元',
            'amount'      => $amount,
            'create_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->insert('user_account', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10021, 'error' => '下单失败，余额变动记录失败！'));
        }

        return true;
    }

    /**
     * 金币支付
     */
    public function _balance_of_score($userinfo, $score)
    {
        if ($userinfo['score'] < $score) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10022, 'error' => '金币不足！'));
        }

        // 扣除余额
        $data = array(
            'score'       => $userinfo['score'] - $score,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $this->user_id)->update('user', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10020, 'error' => '下单失败，金币支付失败！'));
        }

        // 如果是余额支付 - 记录账户变动记录
        $data = array(
            'user_id'     => $this->user_id,
            'action'      => 'user.score.lose',
            'action_name' => '订单支付金币' . $score,
            'amount'      => $score,
            'create_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->insert('user_account', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10021, 'error' => '下单失败，金币变动记录失败！'));
        }

        return true;
    }

    /**
     * 发送微信消息
     */
    public function send_wx_news($cart, $order)
    {
        $articles = array(
            array(
                "title"       => "下单 " . $order['order_sn'] . " 成功",
                "description" => "恭喜发财，您的订单提交成功了，请点击查看您的订单详情！",
                "url"         => site_url('order/index/' . $order['order_sn']),
                "picurl"      => __PUBLIC__ . "image/order.jpg",
            ),
        );
        $goods_ids = array();
        foreach ($cart as $i) {
            $goods_ids[] = $i['goods_id'];
        }
        $goods_arr = $this->db->where('goods_id', 'in', $goods_ids)->result('goods');
        foreach ($goods_arr as $goods) {
            $articles[] = array(
                "title"       => $goods['goods_name'],
                "description" => $goods['goods_name'],
                "url"         => site_url('order/index/' . $order['order_sn'] . '/' . $goods['goods_id']),
                "picurl"      => IMAGED . $goods['thumb'],
            );
        }
        $this->weixin->custom_news($this->wx_openid, $articles);
    }

}
