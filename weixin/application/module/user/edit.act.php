<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置
 * http://host/weixin/test.php/user/setting
 */
class UserEdit extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_edit()
    {
        if (isset($_POST['submit'])) {

            //输入
            $nickname = get_post('nickname');
            $email    = get_post('email');
            $sex      = get_post('sex');
            $birthday = get_post('birthday');
            // $mobile   = get_post('mobile');

            //检查输入
            if (!$this->user_id || !$nickname || !$email || !$sex) {
                // if (!$this->user_id || !$nickname || !$email || !$sex || !$mobile) {
                $this->error_message('参数不能为空');
            }

            //入库
            //少了卡号，生日
            $data = array(
                // 'mobile'   => $mobile,
                'email'    => $email,
                'nickname' => $nickname,
                'sex'      => $sex,
            );
            $result = $this->db->where('user_id', $this->user_id)->update('user', $data);
            if ($result === false) {
                $this->error_message('修改出错，请联系管理人员');
            }
            $this->success_message('修改成功');
            return true;
        }

        $user_row = $this->db->where('user_id', $this->user_id)->row('user');
        foreach ($user_row as $key => $value) {
            $this->view->assign($key, $value);
        }
        $this->view->display('user/edit.html', $user_row);
    }

}
