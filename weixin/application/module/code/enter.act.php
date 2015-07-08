<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 
 * http://host/weixin/test.php/code/enter
 */
class CodeEnter extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_enter() {
        $code = $_REQUEST['code'];
        if ($code !== NULL) {            
            $errmsg = $this->checkCode($code);
            if (empty($errmsg)) {
                $userinfo = $this->db->where('user_id', $this->user_id)->row('user');
                $resAddScore = $this->addScore($userinfo);
                if (FALSE === $resAddScore) {
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

    private function checkCode($code) {
        if (empty($code)) {
            return "优惠码不能为空";
        }

        if ($code != '5031505301298') {
            return "优惠码不正确";
        }

        return "";
    }

    private function addScore($user) {
        if (empty($user)) {
            return FALSE;
        }
        $score = 10;
        $data = array(
            'score' => $user['score'] + $score,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $user['user_id'])->update('user', $data);
        if ($res) {
            // 记录账户变动记录
            $data = array(
                'user_id' => $user['user_id'],
                'action' => 'user.score.add',
                'action_name' => '增加金币' . $score,
                'amount' => $score,
                'create_time' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('user_account', $data);
        }
        
        return $res;
    }

}
