<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 商品详情
 * http://host/weixin/test.php/goods
 */
class GoodsFocus extends Action
{
    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_focus($goods_id = 0)
    {
        $focus_res = array(
            'error'      => 0,
            'reg_status' => 1,
        );

        // 检查注册
        $user_mobile = $this->db->where('user_id', $this->user_id)->column('user', 'mobile');
        if (!$user_mobile) {
            $focus_res['reg_status'] = 0;
            die_json($focus_res);
        }

        //检查是否已经关注
        $goods_focus = $this->db->where('user_id', $this->user_id)->where('goods_id', $goods_id)->row('goods_focus');
        if ($goods_focus) {
            $focus_res['error'] = '您已经关注过该商品了！';
            die_json($focus_res);
        }

        $data = array(
            'user_id'     => $this->user_id,
            'goods_id'    => $goods_id,
            'create_time' => date("Y-m-d H:i:s"),
        );
        $focus_goods_res = $this->db->insert('goods_focus', $data);
        if (!$focus_goods_res) {
            $focus_res['error'] = '关注商品失败！';
            die_json($focus_res);
        }

        die_json($focus_res);
    }

}
