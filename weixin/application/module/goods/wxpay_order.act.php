<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * ΢��֧��ҳ��
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
            logger('����order_sn' . $order_sn);
            return false;
        }

        include_once LIBS_DIR . 'wxpay/lib/WxPay.Api.php';
        include_once LIBS_DIR . 'wxpay/unit/WxPay.JsApiPay.php';
		
		//������Ϣ
		$order = $this->_get_order_info($order_sn);
        //ͳһ����������
        $subject    = $attach    = $order_sn;
        $total_fee  = $order['amount'] * 100; //��λ��
        $notify_url = site_url('goods/wxpay_notify'); //�ص���ַ
        $date       = date("YmdHis");
        //����ͳһ����
        $input = new WxPayUnifiedOrder();
        $input->SetBody($subject);
        $input->SetAttach($attach);
        $input->SetOut_trade_no(WxPayConfig::MCHID . $date);
        $input->SetTotal_fee($total_fee);
        $input->SetTime_start($date);
        $input->SetTime_expire(date("YmdHis", time() + 600)); //������Ч��10����
        $input->SetGoods_tag("tag");
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type("JSAPI");
		
		$tools           = new JsApiPay();
		$openId = $tools->GetOpenid();
		logger("ͳһ�Ķ�������=" . var_export($openId,true));
		$input->SetOpenid($openId);
        //$input->SetOpenid($this->_get_openid());
        //����������ͳһ�Ķ�������
        $order = WxPayApi::unifiedOrder($input);
        
        //�ɶ�����������js����Ĳ���
        
        $jsApiParameters = $tools->GetJsApiParameters($order);
logger("ͳһ�Ķ�������=" . var_export($jsApiParameters,true));
        $this->view->assign('jsApiParameters', $jsApiParameters);
        $this->view->display('goods/wxpay_order.html');
    }

}
