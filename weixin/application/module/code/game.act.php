<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 *
 * http://host/weixin/test.php/code/game
 */
class CodeGame extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_game($fold = '', $game_id = null)
    {
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

            // 修改用户金币
            $this->_update_user_score($userinfo, $score);

            // 提交事务
            $this->db->trans_commit();

            die_json(array('message' => '恭喜您，成功赢取 ' . $score . "金币"));
        }

        if (!$fold || !$game_id) {
            return false;
        }

        //游戏规则
        $game_config = $this->db->where('game_id', $game_id)->column('games', 'game_config');
        $game_config = json_decode($game_config, true);

        $this->view->assign('game_config', $game_config);
        $this->view->assign('game_id', $game_id);
        $this->view->display('code/' . $fold . '/index.html');
    }

    /**
     * 用户信息
     */
    public function _get_user_info()
    {
        $userinfo = $this->db->where('user_id', $this->user_id)->row('user');
        if (!$userinfo) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10012, 'error' => '用户不存在！'));
        }
        return $userinfo;
    }

    /**
     * 修改金币
     */
    public function _update_user_score($userinfo, $score)
    {
        // 修改金币
        $data = array(
            'score'       => $userinfo['score'],
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $this->user_id)->update('user', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10020, 'error' => '获取金币失败！'));
        }

        // 如果是余额支付 - 记录账户变动记录
        $data = array(
            'user_id'     => $this->user_id,
            'action'      => 'user.score.add',
            'action_name' => '增加金币' . $score,
            'amount'      => $score,
            'create_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->insert('user_account', $data);
        if ($res === false) {
            $this->db->trans_rollback(); // 回滚事务
            die_json(array('error_code' => 10021, 'error' => '游戏金币记录失败！'));
        }

        return true;
    }

}
