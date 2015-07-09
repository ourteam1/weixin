<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置
 * http://host/weixin/test.php/user/setting
 */
class UserBind_mobile extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_bind_mobile()
    {
        if (isset($_REQUEST['submit'])) {
            $mobile    = get_post('mobile');
            $checkcode = get_post('checkcode');

            //检查输入
            if (!$mobile || !$checkcode) {
                $this->error_message('缺少参数!');
            }

            $check_result = $this->check_checkcode($mobile, $checkcode);
            if (isset($check_result['error'])) {
                $this->error_message($check_result['error']);
            }

            //绑定手机号码
            $result = $this->db->where('user_id', $this->user_id)->update('user', array('mobile' => $mobile));
            if ($result === false) {
                $this->error_message('绑定手机号码失败');
            }

            $this->success_message('绑定成功', 'user/index');
            return true;
        }
        $this->view->display('user/bind_mobile.html');
    }

    public function check_checkcode($mobile, $user_checkcode)
    {
        $checkcode = $_SESSION['checkcode'];
        if (empty($checkcode)) {
            return array('error' => '请获取验证码');
        }

        list($checkcode, $timestamp) = explode('_', $checkcode);

        //检查超时 5分钟
        $now      = time();
        $overtime = 5 * 60;
        if ($now - $timestamp > $overtime) {
            return array('error' => '验证码已超时，请重新获取');
        }

        if ($checkcode == $user_checkcode) {
            unset($_SESSION['checkcode']);
            return true;
        } else {
            return array('error' => '验证码不正确');
        }

    }
}
