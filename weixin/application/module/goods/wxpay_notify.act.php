<?php
require_once LIBS_DIR . 'wxpay/lib/WxPay.Api.php';
require_once LIBS_DIR . 'wxpay/lib/WxPay.Notify.php';

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

class PayNotifyCallBack extends WxPayNotify
{
    protected $action;

    public function __construct($action)
    {
        $this->action = $action;
        parent::__construct();
    }

    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        logger("query:" . json_encode($result));
        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS") {
            return true;
        }
        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        logger("call back:" . json_encode($data));
        if (!array_key_exists("transaction_id", $data)) {
            logger("输入参数不存在transaction_id");
            return false;
        }

        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            logger("订单查询失败:transaction_id=" . $data["transaction_id"]);
            return false;
        }

        //检查交易状态
        if ($data['result_code'] != 'SUCCESS') {
            logger("交易状态为false" . $data['result_code']);
            return false; //不写库
        }

        $order_sn = $data['attach']; //订单编号

        //检查是否存在订单
        if (!$this->action->db->where('order_sn', $order_sn)->row('order')) {
            logger("不存在支付记录！");
            return false;
        }

        //更新订单:is_pay
        $res = $this->action->db->where('order_sn', $order_sn)->update('order', array('is_pay' => 1));
        if ($res === false) {
            logger("更新订单失败：order_sn=" . $order_sn);
            return false;
        }

        return true;
    }
}

/**
 * 微信支付回调接口
 * http://host/weixin/test.php/goods
 */
class GoodsWxpay_notify extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_wxpay_notify()
    {
        $notify = new PayNotifyCallBack($this);
        $notify->Handle(false);
    }

}
