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
     * 修改游戏配置
     */
    public function update($game_id)
    {
        $update = $this->input->post('update');
        // var_dump($update);die;
        if ($update) {
            $res = $this->load_model('game_model')->update_game($game_id, $update);
            $this->session->set_flashdata('success_message', '更新成功');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $game = $this->load_model('game_model')->get_game_by_game_id($game_id);
        $this->view->assign($game);

        $this->view->display('game/game_' . $game_id . '.html');
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
