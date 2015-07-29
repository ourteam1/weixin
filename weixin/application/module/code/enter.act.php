<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 *
 * http://host/weixin/test.php/code/enter
 */
class CodeEnter extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_enter()
    {
        $code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
        if (!empty($code)) {
            $errmsg = $this->checkCode($code);

            //如果是金币则为数字
            if (is_int($errmsg)) {
                $score       = $errmsg;
                $userinfo    = $this->db->where('user_id', $this->user_id)->row('user');
                $resAddScore = $this->addScore($userinfo, $score);
                if (false === $resAddScore) {
                    $errmsg = "添加金币失败";
                    logger("添加金币失败, userinfo=" . var_export($userinfo, true) . ", code=[" . $code . "]");
                } else {
                    $successmsg = "金币兑换成功";
                }
            }
        }

        $this->view->assign('errmsg', $errmsg);
        $this->view->assign('successmsg', $successmsg);
        $this->view->display('code/enter.html');
    }

    private function checkCode($code)
    {
        if (empty($code)) {
            return "优惠码不能为空";
        }

        //检查验证码
        $check_url                  = 'http://42.62.73.239:8080/CloudServer/wi.do?method=GetCodeState_WX&code=' . $code;
        $check_result               = file_get_contents($check_scancode_url);
        list($check_status, $score) = explode(',', $check_result);
        if ($check_status == '1') {
            return '金币券已被使用了';
        }
        if ($check_status == '2') {
            return '金币券已经过期了';
        }

        if ($check_status != '0') {
            return '金币券异常！';
        }

        $score = empty($score) ? 0 : intval($score);
        return $score;
    }

    private function addScore($user, $score)
    {
        if (empty($user)) {
            return false;
        }
        // $score = 10;
        $data = array(
            'score'       => $user['score'] + $score,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $user['user_id'])->update('user', $data);
        if ($res) {
            // 记录账户变动记录
            $data = array(
                'user_id'     => $user['user_id'],
                'action'      => 'user.score.add',
                'action_name' => '增加金币' . $score,
                'amount'      => $score,
                'create_time' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('user_account', $data);
        }

        return $res;
    }

}
