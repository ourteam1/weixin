<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Category extends MS_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 菜品列表
     */
    public function index()
    {
        $list_data = $this->load_model('category_model')->get_category_list();
        $this->view->assign($list_data);
        $this->view->display('category/category_index.html');
    }

    /**
     * 添加菜品
     */
    public function add()
    {
        $category = $this->input->post('Category');
        if ($category) {
            $res = $this->load_model('category_model')->add_category($category);
            if (isset($res['error'])) {
                $this->session->set_flashdata('category', $category);
                $this->session->set_flashdata('error_message', $res['error']);
                redirect('category/add');
            }
            redirect('category/index');
        } else {
            $location = $this->load_model('category_model')->get_max_location();
            $this->view->assign('location', intval($location) + 1);
        }

        if ($this->session->flashdata('category')) {
            $this->view->assign($this->session->flashdata('category'));
        }

        $this->view->display('category/category_add.html');
    }

    /**
     * 更新菜品
     */
    public function update($category_id = null)
    {
        $category = $this->input->post('Category');

        if ($category) {
            $res = $this->load_model('category_model')->update_category($category_id, $category);

            if (isset($res['error'])) {
                $this->session->set_flashdata('error_message', $res['error']);
            } else {
                $this->session->set_flashdata('success_message', $res['message']);
            }
            $this->session->set_flashdata('category', $category);
            redirect(current_url());
        }

        // 设置闪存数据
        if ($this->session->flashdata('category')) {
            $this->view->assign($this->session->flashdata('category'));
        } else {
            $category = $this->load_model('category_model')->get_category_by_id($category_id);
            $this->view->assign($category);
        }

        $this->view->display('category/category_update.html');
    }

    /**
     * 删除菜品
     */
    public function delete($category_id = null)
    {
        $res = $this->load_model('category_model')->delete_category($category_id);

        if (isset($res['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', $res['message']);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

}
