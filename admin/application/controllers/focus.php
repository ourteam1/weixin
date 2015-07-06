<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Focus extends MS_Controller {

	function __construct() {
		parent::__construct();
	}

	/**
	 * 关注列表
	 */
	function index() {
		$list_data = $this->load_model('focus_model')->get_category_list();
		$this->view->assign($list_data);
		$this->view->display('focus/index.html');
	}

	/**
	 * 添加菜品
	 */
	function add() {
        
		$focus = $this->input->post('Focus');
		if ($focus) {
			$res = $this->load_model('focus_model')->add_focus($focus);
			if (isset($res['error'])) {
				$this->session->set_flashdata('focus', $focus);
				$this->session->set_flashdata('error_message', $res['error']);
				redirect('focus/add');
			}
			redirect('focus/index');
		}
		else {
			$sort = $this->load_model('focus_model')->get_max_sort();
			$this->view->assign('sort', intval($sort) + 1);
		}

		if ($this->session->flashdata('focus')) {
			$this->view->assign($this->session->flashdata('focus'));
		}

		$this->view->display('focus/add.html');
	}

	/**
	 * 更新关注
	 */
	function update($focus_id = null) {
		$focus = $this->input->post('Focus');

		if ($focus) {
			$res = $this->load_model('focus_model')->update_category($focus_id, $focus);

			if (isset($res['error'])) {
				$this->session->set_flashdata('error_message', $res['error']);
			}
			else {
				$this->session->set_flashdata('success_message', $res['message']);
			}
			$this->session->set_flashdata('focus', $focus);
			redirect(current_url());
		}

		// 设置闪存数据
		if ($this->session->flashdata('focus')) {
			$this->view->assign($this->session->flashdata('focus'));
		}
		else {
			$focus = $this->load_model('focus_model')->get_focus_by_id($focus_id);
			$this->view->assign($focus);
		}

		$this->view->display('focus/update.html');
	}

	/**
	 * 删除关注
	 */
	public function delete($focus_id = null) {
		$res = $this->load_model('focus_model')->delete_focus($focus_id);
		if (isset($res['error'])) {
			$this->session->set_flashdata('error_message', $res['error']);
		}
		else {
			$this->session->set_flashdata('success_message', $res['message']);
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

}
