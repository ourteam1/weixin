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
        if (isset($_POST['submit'])) {
            $mobile    = get_post('mobile');
            $checkcode = get_post('checkcode');

            //检查输入
            if (!$mobile || !$checkcode) {
                $this->error_message('缺少参数!');
            }

            if (!$this->check_checkcode($mobile, $checkcode)) {
                $this->error_message('验证码不正确，请重试!');
            }

            //绑定手机号码
            $result = $this->db->where('user_id', $this->user_id)->update('user', array('mobile' => $mobile));
            if ($result === false) {
                $this->error_message('绑定手机号码');
            }

            $this->success_message('绑定成功');
            return true;
        }
        $this->view->display('user/bind_mobile.html');
    }

    public function check_checkcode($mobile, $checkcode)
    {
        if ($_SESSION['checkcode'] == $checkcode) {
            return true;
        } else {
            return false;
        }
    }
}
