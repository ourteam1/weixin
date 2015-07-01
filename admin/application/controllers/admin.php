<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MS_Controller {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 管理员列表
	 */
	public function index($offset = 0) {
		$list_data = $this->load_model('admin_model')->get_admin_list($offset);
		$this->view->assign($list_data);
		$this->view->display('system/admin_index.html');
	}

	/**
	 * 添加管理员
	 */
	public function add() {
		$admin = $this->input->post('Admin');

		if ($admin) {
			$res = $this->load_model('admin_model')->add_admin($admin);
			if (isset($res['error'])) {
				$this->session->set_flashdata('admin', $admin);
				$this->session->set_flashdata('error_message', $res['error']);
				redirect(current_url());
			}
			redirect('admin/add');
		}

		if ($this->session->flashdata('admin')) {
			$this->view->assign($this->session->flashdata('admin'));
		}

		// 权限分组
		$group_actions = $this->config->item('group_actions');
		$this->view->assign('group_actions', $group_actions);

		// 权限点
		$access_actions = $this->config->item('access_actions');
		$this->view->assign('access_actions', $access_actions);

		$this->view->display('system/admin_add.html');
	}

	/**
	 * 修改管理员
	 */
	public function update($admin_id) {
		$admin = $this->input->post('Admin');

		if ($admin) {
			$res = $this->load_model('admin_model')->update_admin($admin_id, $admin);
			if (isset($res['error'])) {
				$this->session->keep_flashdata('from_list_page');
				$this->session->set_flashdata('admin', $admin);
				$this->session->set_flashdata('error_message', $res['error']);
				redirect(current_url());
			}
			$this->session->set_flashdata('success_message', '操作成功');
			redirect($this->session->flashdata('from_list_page'));
		}
		$this->session->set_flashdata('from_list_page', $_SERVER['HTTP_REFERER']);

		if ($this->session->flashdata('admin')) {
			$this->view->assign($this->session->flashdata('admin'));
		}
		else {
			$admin = $this->load_model('admin_model')->get_admin_by_id($admin_id);
			$this->view->assign($admin);
		}

		// 权限分组
		$group_actions = $this->config->item('group_actions');
		$this->view->assign('group_actions', $group_actions);

		// 权限点
		$access_actions = $this->config->item('access_actions');
		$this->view->assign('access_actions', $access_actions);

		$this->view->assign('admin_id', $admin_id);
		$this->view->display('system/admin_update.html');
	}

	/**
	 * 删除管理员
	 */
	public function delete($admin_id) {
		$res = $this->load_model('admin_model')->delete_admin($admin_id);
		if (isset($res['error'])) {
			$this->session->set_flashdata('error_message', $res['error']);
		}
		else {
			$this->session->set_flashdata('success_message', '操作成功！');
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * 修改密码
	 */
	public function password() {
		$admin = $this->input->post('Admin');
		if ($admin) {
			$res = $this->load_model('admin_model')->update_password($admin);
			if (isset($res['error'])) {
				$this->session->set_flashdata('error_message', $res['error']);
			}
			else {
				$this->session->set_flashdata('success_message', '密码修改成功！');
			}
			redirect('admin/password');
		}

		$this->view->display('system/admin_password.html');
	}

}
