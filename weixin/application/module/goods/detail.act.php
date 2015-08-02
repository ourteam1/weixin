<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 商品详情
 * http://host/weixin/test.php/goods
 */
class GoodsDetail extends Action
{
    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_detail($goods_id = 0)
    {
        // 商品信息
        $goods = $this->db->where('status', 1)->where('goods_id', $goods_id)->row('goods');

        //是否已被关注
        $goods_focus = $this->db->where('user_id', $this->user_id)->where('goods_id', $goods_id)->row('goods_focus');

        $this->view->assign('goods_focus', $goods_focus);
        $this->view->assign('goods', $goods);
        $this->view->display('goods/goods_detail.html');
    }

}
