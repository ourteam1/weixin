<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 
 * http://host/weixin/test.php/code/enter
 */
class CodeFocus extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_focus($code = '') {
        if (empty($code)) {
            die_json(array('error_code' => 1, 'error' => '参数错误'));
        }

        // 是否关注
        $focusInfo = $this->db->where('user_id', $this->user_id)->where('code', $code)->row('focus_user');
        if ($focusInfo) {
            die_json(array('message' => '已经关注'));
        }

        // 启动事务
        $this->db->trans_start();

        $freeData = array(
            'code' => $code,
            'user_id' => $this->user_id,
            'create_time' => date('Y-m-d H:i:s')
        );
        $res = $this->db->insert("focus_user", $freeData);
        if (FALSE === $res) {
             $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 3, 'error' => '关注失败'));
        }
        
        $userinfo = $this->db->where('user_id', $this->user_id)->row('user');
        $score = !empty($focusInfo['score']) ? $focusInfo['score'] : 0;        
        $data = array(
            'score' =>  $userinfo['score'] + $score,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $this->user_id)->update('user', $data);
        if (false === $res) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 4, 'error' => '增加金币失败'));
        }

        // 记录账户变动记录
        $data = array(
            'user_id' => $this->user_id,
            'action' => 'user.score.add',
            'action_name' => '增加金币' . $score ,
            'amount' => $score,
            'create_time' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('user_account', $data);
        
        // 提交事务
        $this->db->trans_commit();
        
        die_json(array('message'=>'关注成功'));
    }

}
