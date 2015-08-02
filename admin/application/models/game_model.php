<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Game_model extends MS_Model
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function update_game($game_id, $update)
    {
        if ($game_id == 1) {
            $tmp = 0;
            foreach ($update['proba_arr'] as $value) {
                $tmp += $value;
            }

            //不中奖的概率
            $update['proba_arr'][7] = 1000 - $tmp;

            // $config = array(
            //     'proba_arr'  => $update['proba_arr'],
            //     'play_times' => $update['play_times'],
            //     'week_play'  => $update['week_play'],
            //     'money_arr'  => $update['money_arr'],
            // );
        }

        $data = array(
            'game_config' => json_encode($update),
            'modify_time' => date("Y-m-d H:i:s"),
        );
        return $this->db->where('game_id', $game_id)->update('games', $data);
    }

    /**
     * 分页列表
     */
    public function get_game_by_game_id($game_id)
    {
        $game   = $this->db->get_where('games', array('game_id' => $game_id))->row();
        $config = json_decode($game->game_config, true);
        return compact('game', 'config');
    }

    /**
     * 分页列表
     */
    public function get_game_list($offset = 0)
    {
        // 计算总数
        $count = $this->db->get('games')->num_rows();

        // 条件、排序
        $this->db->order_by('game_id', 'desc');

        // 分页查询数据
        $this->db->limit(PAGE_LIMIT, $offset);
        $list_data = $this->db->get('games')->result();

        // 分页
        $this->pagination->set_base_url(site_url('game/index'));
        $this->pagination->set_total_rows($count);
        $pagination = $this->pagination->get_links();

        return array('list_data' => $list_data, 'count' => $count, 'pagination' => $pagination);
    }

    /**
     * 添加游戏配置
     */
    public function add_game($game_name, $game_icon, $game_config)
    {

        $data = array(
            'game_name'   => $game_name,
            'game_icon'   => $game_icon,
            'game_config' => json_encode($game_config),
        );
        $res = $this->db->insert('games', $data);
        return $res ? $this->db->insert_id() : array('error' => '添加游戏配置失败');
    }

    /**
     * 删除游戏配置
     */
    public function delete_game($game_id)
    {
        $game_row = $this->db->where('game_id', $game_id)->get('games')->row_array();
        if (!$game_row) {
            return array('error' => '不存在游戏配置！');
        }

        $res = $this->db->where('game_id', $game_id)->delete('games');
        if ($res === false) {
            return array('error' => '删除游戏配置失败！');
        }

        // 写日志
        $this->load_model('logs_model')->add_content("删除游戏配置：" . $game_row['game_name'] . '-' . $game_row['game_config']);

        return true;
    }
}
