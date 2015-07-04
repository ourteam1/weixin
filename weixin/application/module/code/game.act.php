<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 
 * http://host/weixin/test.php/code/game
 */
class CodeGame extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_game() {
        $score = $_REQUEST['score'];
        if (!empty($score)) {
            $score = intval($score);
            if (empty($score)) {
                die_json(array('message' => '谢谢参与'));
            }

            // 启动事务
            $this->db->trans_start();

            // 用户信息
            $userinfo = $this->_get_user_info();

            $userinfo['score'] += $score;

            // 修改用户积分
            $this->_update_user_score($userinfo, $score);

            // 提交事务
            $this->db->trans_commit();
            
            die_json(array('message' => '恭喜您，成功赢取 ' . $score . "金币"));
        }

        $this->view->display('code/game.html');
    }

    /**
     * 用户信息
     */
    function _get_user_info() {
        $userinfo = $this->db->where('user_id', $this->user_id)->row('user');
        if (!$userinfo) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10012, 'error' => '用户不存在！'));
        }
        return $userinfo;
    }
    
    /**
     * 修改积分
     */
    function _update_user_score($userinfo, $score) {
        // 修改积分
        $data = array(
            'score' => $userinfo['score'],
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $this->user_id)->update('user', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10020, 'error' => '获取积分失败！'));
        }

        // 如果是余额支付 - 记录账户变动记录
        $data = array(
            'user_id' => $this->user_id,
            'action' => 'user.score.add',
            'action_name' => '增加积分' . $score,
            'amount' => $score,
            'create_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->insert('user_account', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10021, 'error' => '游戏积分记录失败！'));
        }

        return true;
    }

}
