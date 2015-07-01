<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends MS_Controller {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 用户列表
	 */
	public function index($offset = 0) {
		$param		 = $this->input->post('User');
		$list_data	 = $this->load_model('user_model')->get_user_list($offset, $param);
		$this->view->assign($list_data);
		$this->view->assign('User', $param);
		$this->view->display('user/user_index.html');
	}

	/**
	 * 用户详细信息
	 */
	public function view($user_id) {
		$user = $this->load_model("user_model")->get_user_by_id($user_id);
		$this->view->assign('user', $user);
		$this->view->display('user/user_view.html');
	}

	/**
	 * 用户充值
	 */
	public function recharge($user_id) {
		$money = $this->input->post('money');
		if ($money != null) {
			$res = $this->load_model("user_model")->recharge($user_id, $money);
			if (isset($res['error'])) {
				$this->session->keep_flashdata('from_list_page');
				$this->session->set_flashdata('money', $money);
				$this->session->set_flashdata('error_message', $res['error']);
				redirect(current_url());
			}
			$this->session->set_flashdata('success_message', '充值成功！');
			redirect($this->session->flashdata('from_list_page'));
		}
		$this->session->set_flashdata('from_list_page', $_SERVER['HTTP_REFERER']);

		$user = $this->load_model("user_model")->get_user_by_id($user_id);
		$this->view->assign('user', $user);

		$this->view->display('user/user_recharge.html');
	}

	/**
	 * 账户明细
	 */
	public function account_details($user_id, $offset = 0) {
		$list_data = $this->load_model("user_model")->get_user_account_details($user_id, $offset);
		$this->view->assign($list_data);
		$this->view->display('user/user_account_details.html');
	}

}
