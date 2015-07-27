<?php
if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

include_once LIBS_DIR . 'CCPRestSmsSDK.class.php';

/**
 * 设置手机验证码
 * http://host/weixin/test.php/user/setting
 */
class UserGet_checkcode extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_get_checkcode($mobile = '')
    {
        if (!$mobile || !preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|18[0-9]{9}$/", $mobile)) {
            die_json(array('error' => '手机号格式不正确'));
        }

        // session_start();
        if (isset($_SESSION['checkcode'])) {
            $got_checkcode               = $_SESSION['checkcode'];
            list($checkcode, $timestamp) = explode('_', $got_checkcode);
            $now                         = time();

            //检查是否在一分钟内
            if ($now - $timestamp <= 60) {
                die_json(array('error' => '一分钟后才能重发验证码'));
            }
        }

        $checkcode             = rand(100000, 999999); //6位
        $timestamp             = time(); //当前时间，5分钟过期
        $_SESSION['checkcode'] = $checkcode . '_' . $timestamp;

        //调用短信
        $result = $this->sendTemplateSMS($mobile, array($checkcode, '5'), $debug = 1); //替换debug
        if (!$result) {
            die_json(array('error' => '发送故障，请稍候重试'));
        } else {
            die_json(array('success' => '已发送验证码，请在' . $mobile . '手机上查收验证码'));
        }
        return true;
    }

    public function sendTemplateSMS($to, $datas, $tempId)
    {
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        $accountSid = ACCOUNTSID;

        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        $accountToken = ACCOUNTTOKEN;

        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        $appId = APPID;

        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        $serverIP = SERVERIP;

        //请求端口，生产环境和沙盒环境一致
        $serverPort = SERVERPORT;

        //REST版本号，在官网文档REST介绍中获得。
        $softVersion = SOFTVERSION;

        //开发时注释
        $tempId = TEMPID;

        // 初始化REST SDK
        $rest = new REST($serverIP, $serverPort, $softVersion);
        $rest->setAccount($accountSid, $accountToken);
        $rest->setAppId($appId);

        // 发送模板短信
        $result = $rest->sendTemplateSMS($to, $datas, $tempId);
        if ($result == null) {
            logger("发送短信失败, mobile=" . $to . ", 短信服务器错误");
            return false;
        }

        if ($result->statusCode != 0) {
            logger("发送短信失败, mobile=" . $to . ", errcode=[" . $result->statusCode . "]" . ", errmsg=[" . $result->statusMsg . "]");
            return false;
        } else {
            // 获取返回信息
            $smsmessage = $result->TemplateSMS;
            // echo "dateCreated:" . $smsmessage->dateCreated . "<br/>";
            // echo "smsMessageSid:" . $smsmessage->smsMessageSid . "<br/>";
            //TODO 添加成功处理逻辑
            logger("发送短信成功, mobile=" . $to . ' dateCreated=' . $smsmessage->dateCreated);
            return true;
        }
    }
}
