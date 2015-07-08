<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 优惠卷兑换
 * http://host/weixin/test.php/favorable/add
 */
class FavorableAdd extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_add($code = '') {
        if (!$code) {
            die_json(array('error_code' => 10031, 'error' => '活动编码错误'));
        }

        // 启动事务
        $this->db->trans_start();                
        
        // 活动是否存在
        $activity = $this->_check_activity($code);
        
        // 验证是否已经领过优惠卷
        $favorable = $this->_check_favorable($code);

        // 记录优惠卷信息
        $resFavorable = $this->_add_favorable($activity);

        // 扣减用户积分
        $resUser = $this->_minus_user_score($activity['activity_score']);

        // 扣减日志
        if ($resUser) {
            $this->_add_user_account($activity['activity_score']);
        }
        
        // 提交事务
        $this->db->trans_commit();
        
         // 返回成功信息
        die_json(array('message' => '获取优惠卷成功', 'favorable' => $resFavorable));
    }

    private function _add_user_account($score) {
        $data = array(
            'user_id' => $this->user_id,
            'action' => 'user.score.lose',
            'action_name' => '减少积分' . $score,
            'amount' => $score,
            'create_time' => date('Y-m-d H:i:s'),
        );
        $resUserAccount = $this->db->insert('user_account', $data);
        if (FALSE === $resUserAccount) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10038, 'error' => '用户积分更新记录失败！'));
        }
        
        return $resUserAccount;
    }

    private function _minus_user_score($score) {
        // 用户基本信息
        $userinfo = $this->db->where('user_id', $this->user_id)->row('user');
        if (!$userinfo) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10035, 'error' => '用户不存在！'));
        }
        if ($userinfo['score'] < $score) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10036, 'error' => '金币不足，无法兑换！'));
        }
        $data = array(
            'score' => $userinfo['score'] - $score,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $user['user_id'])->update('user', $data);
        if (FALSE === $res) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10037, 'error' => '用户扣除金币失败！'));
        }

        return $res;
    }

    private function _add_favorable($activity) {
        var_dump($activity);
        $url = "http://42.62.73.239:8080/CloudServer/wi.do?method=GetDiscountCode_DX&hdbh=" . $activity['activity_code'];
        $favorableCode = @file_get_contents($url);

        if (empty($favorableCode)) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10033, 'error' => '领取优惠卷失败！'));
        }
        
        $favorableData = array(
            'user_id' => $this->user_id,
            'activity_code' => $activity['activity_code'],
            'favorable_code' => $favorableCode,
            'create_time' => date('Y-m-d H:i:s'),
        );

        $res = $this->db->insert('favorable', $favorableData);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10034, 'error' => '领取优惠卷失败22！'));
        }
        $favorableData['favorable_id'] = $this->db->last_insert_id();
        return $favorableData;
    }

    private function _check_activity($code) {
        $activityInfo = $this->db->where('activity_code', $code)->row('activity');
        if (empty($activityInfo)) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10032, 'error' => '优惠活动不存在！'));
        }
        
        return $activityInfo;
    }
    
    private function _check_favorable($code) {
        $favorableInfo = $this->db->where('user_id', $this->user_id)->where('activity_code', $code)->row('activity');
        if ($favorableInfo) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10039, 'error' => '优惠卷已经领取过了！'));
        }
        
        return $favorableInfo;
    }

}
