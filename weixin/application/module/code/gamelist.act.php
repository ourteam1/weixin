<?php
if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 *
 * http://host/weixin/test.php/code/gamelist
 */
class CodeGamelist extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_gamelist()
    {
        // $games = array(
        //     array('game_id' => 1, 'name' => '摇一摇得金币', 'fold' => 'yao', 'icon' => 'app/yao/icon.png'),
        //     array('game_id' => 2, 'name' => '开心刮刮乐', 'fold' => 'money', 'icon' => 'app/money/icon.png'),
        //     array('game_id' => 3, 'name' => '抽奖赢金币', 'fold' => 'choujiang', 'icon' => 'app/choujiang/icon.png'),
        // );
        $games = $this->db->result('games');
        foreach ($games as $key => $game) {
            $games[$key]['game_config'] = json_decode($game['game_config'], true);
        }
        $this->view->assign('week_today', date('N'));
        $this->view->assign('games', $games);
        $this->view->display('code/gamelist.html');
    }

}
