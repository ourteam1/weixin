<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Goods extends MS_Controller
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 确定推送产品
     */
    public function push($goods_id = null)
    {
        if (!$goods_id) {
            $this->session->set_flashdata('warning_message', '错误的参数！');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $res = $this->load_model('goods_model')->push_goods($goods_id, 1);
        if (isset($res['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', $res['message']);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * 商品列表
     */
    public function index($offset = 0)
    {
        $query = $this->input->post('Query');
        $this->view->assign('query', $query);

        $category_data = $this->load_model('category_model')->get_category_order_by_location();
        $this->view->assign('categorys', $category_data);

        $list_data = $this->load_model('goods_model')->get_list_data($offset, $query);
        $this->view->assign($list_data);
        $this->view->display('goods/goods_index.html');
    }

    /**
     * 添加商品
     */
    public function add()
    {
        $goods = $this->input->post('Goods');

        if ($goods) {
            $res = $this->load_model('goods_model')->add($goods);
            if (isset($res['error'])) {
                $this->session->set_flashdata('error_message', $res['error']);
                $this->session->set_flashdata('goods', $goods);
                redirect(current_url());
            }
            $this->session->set_flashdata('success_message', $res['message']);
            redirect('goods/index');
        }

        // 设置闪存数据
        if ($this->session->flashdata('goods')) {
            $this->view->assign($this->session->flashdata('goods'));
        }

        $categorys = $this->load_model('category_model')->get_category_order_by_location();
        $this->view->assign('categorys', $categorys);

        $this->view->display('goods/goods_add.html');
    }

    /**
     * 更新商品
     */
    public function update($goods_id = null)
    {
        $goods = $this->input->post('Goods');

        if ($goods) {
            $res = $this->load_model('goods_model')->update($goods_id, $goods);
            if (isset($res['error'])) {
                $this->session->keep_flashdata('from_list_page');
                $this->session->set_flashdata('error_message', $res['error']);
                $this->session->set_flashdata('goods', $goods);
                redirect(current_url());
            }
            $this->session->set_flashdata('success_message', $res['message']);
            redirect($this->session->flashdata('from_list_page'));
        }
        $this->session->set_flashdata('from_list_page', $_SERVER['HTTP_REFERER']);

        // 设置闪存数据
        if ($this->session->flashdata('goods')) {
            $this->view->assign($this->session->flashdata('goods'));
        } else {
            $goods = $this->load_model('goods_model')->get_goods_by_id($goods_id);
            $this->view->assign($goods);
        }

        $categorys = $this->load_model('category_model')->get_category_order_by_location();
        $this->view->assign('categorys', $categorys);

        $this->view->display('goods/goods_update.html');
    }

    /**
     * 删除商品
     */
    public function delete($goods_id = null)
    {
        if (!$goods_id) {
            $this->session->set_flashdata('warning_message', '错误的参数！');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $res = $this->load_model('goods_model')->delete_goods($goods_id);
        if (isset($res['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', $res['message']);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * 商品上架
     */
    public function grounding($goods_id = null)
    {
        if (!$goods_id) {
            $this->session->set_flashdata('warning_message', '错误的参数！');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $res = $this->load_model('goods_model')->update_goods_status($goods_id, 1);
        if (isset($res['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', $res['message']);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * 商品下架
     */
    public function undercarriage($goods_id = null)
    {
        if (!$goods_id) {
            $this->session->set_flashdata('warning_message', '错误的参数！');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $res = $this->load_model('goods_model')->update_goods_status($goods_id, 2);
        if (isset($res['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', $res['message']);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

}
