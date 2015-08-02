<?php
if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 *
 * http://host/weixin/test.php/code/enter
 */
class CodeGet_game_result extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    /**
     * 获取游戏结果
     */
    public function on_get_game_result()
    {
        $game_id = get_post('game_id');
        if (!$game_id) {
            return false;
        }
        $game_config = $this->db->where('game_id', $game_id)->column('games', 'game_config');
        $game_config = json_decode($game_config, true);
        $rid         = $this->getRand($game_config['proba_arr']);
        if ($rid > 6) {
            $rid = 0;
        }

        die_json(array('rid' => $rid));
    }

    /**
     * 中奖概率计算, 能用
     * $proArr = array('1'=>'概率', '2'=>'概率');
     * $proCount = array('1'=>'库存', '2'=>'库存');
     */
    public function getRand($proArr, $proCount = array())
    {
        $result = '';
        $proSum = 1000;
        // foreach ($proCount as $key => $val) {
        //     if ($val <= 0) {
        //         continue;
        //     } else {
        //         $proSum = $proSum + $proArr[$key];
        //     }
        // }
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
            // if ($proCount[$key] <= 0) {
            //     continue;
            // } else {
            //     $randNum = mt_rand(1, $proSum);
            //     if ($randNum <= $proCur) {
            //         $result = $key;
            //         break;
            //     } else {
            //         $proSum -= $proCur;
            //     }
            // }
        }
        unset($proArr);
        return $result;
    }
}
