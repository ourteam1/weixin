<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 微信支付页面
 * http://host/weixin/test.php/goods
 */
class GoodsWxpay_order extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

	private function _get_openid(){
		return $this->db->where('user_id', $this->user_id)->column('user', 'wx_openid');
	}
	
	private function _get_order_info($order_sn){
		return $this->db->where('order_sn', $order_sn)->row('order');
	}	
	
    public function on_wxpay_order()
    {
		$order_sn = get_post('order_id');
        if (empty($order_sn)) {
            logger('错误order_sn' . $order_sn);
            return false;
        }

        include_once LIBS_DIR . 'wxpay/lib/WxPay.Api.php';
        include_once LIBS_DIR . 'wxpay/unit/WxPay.JsApiPay.php';
		
		//订单信息
		$order = $this->_get_order_info($order_sn);
        //统一订单的配置
        $subject    = $attach    = $order_sn;
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
		
		$tools           = new JsApiPay();
		$openId = $tools->GetOpenid();
		logger("统一的订单配置=" . var_export($openId,true));
		$input->SetOpenid($openId);
        //$input->SetOpenid($this->_get_openid());
        //由配置生成统一的订单配置
        $order = WxPayApi::unifiedOrder($input);
        
        //由订单配置生成js请求的参数
        
        $jsApiParameters = $tools->GetJsApiParameters($order);
logger("统一的订单配置=" . var_export($jsApiParameters,true));
        $this->view->assign('jsApiParameters', $jsApiParameters);
        $this->view->display('goods/wxpay_order.html');
    }

}
