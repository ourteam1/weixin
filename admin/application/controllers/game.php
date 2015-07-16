<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Game extends MS_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 游戏配置列表
     */
    public function setting()
    {
        $list_data = $this->load_model('game_model')->get_game_list();
        $this->view->assign($list_data);
        $this->view->display('game/setting.html');
    }

    /**
     * 添加游戏配置
     */
    public function add()
    {
        $game_name   = $this->input->post('game_name');
        $game_icon   = $this->input->post('game_icon');
        $game_config = $this->input->post('game_config');

        $result = $this->load_model('game_model')->add_game($game_name, $game_icon, $game_config);
        if (isset($result['error'])) {
            die_json(array('error' => $result['error']));
        } else {
            die_json(array('error' => 0));
        }
    }

    public function delete()
    {
        $game_id = $this->input->post('game_id');
        $result  = $this->load_model('game_model')->delete_game($game_id);
        if (isset($result['error'])) {
            die_json(array('error' => $result['error']));
        } else {
            die_json(array('error' => 0));
        }
    }
}
