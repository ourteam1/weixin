<?php
if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 *
 * http://host/weixin/test.php/code/enter
 */
class CodeCheck_can_play extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_check_can_play()
    {
        $game_id = get_post('game_id');
        if (!$game_id) {
            return false;
        }

        $key = 'user_play_game_times_' . $this->user_id . '_' . $game_id;
        // unset($_SESSION[$key]);die;
        $can_play = 1;
        if (isset($_SESSION[$key])) {
            echo $_SESSION[$key] . "\n";
            //检查是否过期了
            list($play_times, $expire) = explode(',', $_SESSION[$key]);
            if (time() <= $expire) {
                //没有过期
                if ($play_times == 0) {
                    //今天不能玩了
                    $can_play = 0;
                    die_json(array('can_play' => $can_play));
                } else {
                    $play_times--;
                    $_SESSION[$key] = $play_times . ',' . $expire;
                    die_json(array('can_play' => $can_play));
                }
            }
            //过期了
        }
        //第一次玩

        //获取play_times
        $game_config     = $this->db->where('game_id', $game_id)->column('games', 'game_config');
        $game_config_arr = json_decode($game_config, true);
        $play_times      = $game_config_arr['play_times'];

        $play_times--;
        $expire         = strtotime('+1 day', strtotime(date('Y-m-d'))); //明天000
        $_SESSION[$key] = $play_times . ',' . $expire;
        die_json(array('can_play' => $can_play));
    }
}
