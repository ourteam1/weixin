<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends MS_Model {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 管理员登录
	 */
	function login($login) {
		$account	 = element('account', $login, '');
		$password	 = element('password', $login, '');

		// 基本信息验证
		if (!trim($account)) {
			return array('error_code' => '10010', 'error' => '请填写帐号!');
		}
		if (!trim($password)) {
			return array('error_code' => '10011', 'error' => '请填写密码!');
		}

		// 查询数据库
		$admin = $this->db->where('account', $account)->get('admin')->row_array();
		if (!$admin) {
			return array('error_code' => '10013', 'error' => '帐号错误!');
		}
		if (strtolower(md5($password)) != strtolower($admin['password'])) {
			return array('error_code' => '10014', 'error' => '密码错误!');
		}

		// 更新时间和IP
		$this->db->where('account', $account);
		$this->db->set('last_login_ip', $this->input->ip_address());
		$this->db->set('last_login_time', date('Y-m-d H:i:s'));
		$this->db->set('modify_time', date('Y-m-d H:i:s'));
		$this->db->update('admin');

		// 设置管理员登录会话
		$this->session->set_userdata('MSADMIN_ID', $admin['admin_id']);
		$this->session->set_userdata('MSADMIN_ACCOUNT', $account);

		// 设置管理员权限
		$msadmin_access_actions = '*';
		if ($admin['admin_id'] != 1) {
			$msadmin_access_actions	 = array();
			$access_actions			 = $this->config->item('access_actions');
			$access					 = $this->db->where('admin_id', $admin['admin_id'])->get('access')->result_array();
			foreach ($access as $i) {
				$permission				 = $i['permission'];
				$actions				 = $access_actions[$permission]['actions'];
				$msadmin_access_actions	 = array_merge($msadmin_access_actions, $actions);
			}
		}
		$this->session->set_userdata('MSADMIN_ACCESS_ACTIONS', $msadmin_access_actions);

		// 设置日志
		$this->load_model('logs_model')->add_content('登陆系统');

		return null;
	}

	/**
	 * 管理员退出
	 */
	function logout() {
		// 设置日志
		$this->load_model('logs_model')->add_content('退出系统');
		$this->session->unset_userdata('MSADMIN_ACCOUNT');
		$this->session->sess_destroy();
		return true;
	}

	/**
	 * 分页列表
	 */
	function get_admin_list($offset = 0) {
		// 计算总数
		$this->db->where('admin_id !=', 1);
		$count = $this->db->get('admin')->num_rows();

		// 条件、排序
		$this->db->where('admin_id !=', 1);
		$this->db->order_by('admin_id', 'desc');

		// 分页查询数据
		$this->db->limit(PAGE_LIMIT, $offset);
		$list_data = $this->db->get('admin')->result();

		// 分页
		$this->pagination->set_base_url(site_url('admin/index'));
		$this->pagination->set_total_rows($count);
		$pagination = $this->pagination->get_links();

		return array('list_data' => $list_data, 'count' => $count, 'pagination' => $pagination);
	}

	/**
	 * 查询管理员信息
	 */
	function get_admin_by_id($admin_id) {
		// 查询管理员信息
		$this->db->where('admin_id', $admin_id);
		$admin = $this->db->get('admin')->row_array();

		// 查询管理员权限信息
		$this->db->where('admin_id', $admin_id);
		$access = $this->db->get('access')->result_array();

		// 定义管理员权限
		$admin['permissions'] = array();

		// 整理数据
		if ($access) {
			foreach ($access as $i) {
				$admin['permissions'][] = $i['permission'];
			}
		}

		return $admin;
	}

	/**
	 * 添加管理员
	 */
	function add_admin($admin) {
		// 判断
		if (!isset($admin['account']) || !trim($admin['account'])) {
			return array('error' => '管理员帐号不能为空！');
		}
		if (!isset($admin['password']) || !trim($admin['password'])) {
			return array('error' => '管理员密码不能为空！');
		}

		// 判断帐号是否存在
		$this->db->where('account', $seller['account']);
		$admin_count = $this->db->get('admin')->num_rows();
		if ($admin_count) {
			return array('error' => '管理员帐号已经存在！');
		}

		// 整理数据
		$admin_data = array(
			'account'		 => $admin['account'],
			'password'		 => md5($admin['password']),
			'create_time'	 => date('Y-m-d H:i:s'),
			'modify_time'	 => date('Y-m-d H:i:s'),
		);

		// 添加管理员
		$res = $this->db->insert('admin', $admin_data);
		if ($res === false) {
			return array('error' => '添加管理员信息失败！');
		}

		// 定义管理员ID
		$admin_id = $this->db->insert_id();

		// 添加管理员权限
		if (isset($admin['permissions']) && $admin['permissions']) {
			foreach ($admin['permissions'] as $permission) {
				$access = array(
					'admin_id'	 => $admin_id,
					'permission' => $permission,
				);
				$this->db->insert('access', $access);
			}
		}

		// 添加log
		$this->load_model('logs_model')->add_content("添加管理员帐号" . $admin['account']);

		return array('message' => '操作成功！');
	}

	/**
	 * 修改管理员
	 */
	function update_admin($admin_id, $admin) {
		// 判断
		if ($admin_id == 1) {
			return array('error' => '非法参数！');
		}
		if (!isset($admin['account']) || !trim($admin['account'])) {
			return array('error' => '管理员帐号不能为空！');
		}

		// 判断帐号是否存在
		$this->db->where('account', $seller['account']);
		$this->db->where('admin_id !=', $admin_id);
		$admin_count = $this->db->get('admin')->num_rows();
		if ($admin_count) {
			return array('error' => '管理员帐号已经存在！');
		}

		// 整理数据
		$admin_data = array(
			'account'		 => $admin['account'],
			'modify_time'	 => date('Y-m-d H:i:s'),
		);

		// 如果填写了密码，就修改密码
		if (isset($admin['password']) && trim($admin['password'])) {
			$admin_data['password'] = md5($admin['password']);
		}

		// 修改管理员信息
		$this->db->where('admin_id', $admin_id);
		$res = $this->db->update('admin', $admin_data);
		if ($res === false) {
			return array('error' => '修改管理员信息失败！');
		}

		// 删除已有管理员权限
		$this->db->where('admin_id', $admin_id);
		$this->db->delete('access');

		// 添加管理员权限
		if (isset($admin['permissions']) && $admin['permissions']) {
			foreach ($admin['permissions'] as $permission) {
				$access = array(
					'admin_id'	 => $admin_id,
					'permission' => $permission,
				);
				$this->db->insert('access', $access);
			}
		}

		// 添加log
		$this->load_model('logs_model')->add_content("修改管理员帐号" . $admin['account']);

		return array('message' => '操作成功！');
	}

	/**
	 * 删除管理员
	 */
	function delete_admin($admin_id) {
		// 判断
		if ($admin_id == 1) {
			return array('error' => '非法参数！');
		}

		// 删除管理员信息
		$res = $this->db->where('admin_id', $admin_id)->delete('admin');
		if ($res === false) {
			return array('error' => '管理员删除失败！');
		}

		// 删除管理员权限信息
		$this->db->where('admin_id', $admin_id)->delete('access');

		// 添加log
		$this->load_model('logs_model')->add_content("删除管理员帐号，编号=" . $admin_id);

		return array('message' => '操作成功！');
	}

	/**
	 * 修改密码
	 */
	function update_password($admin) {
		// 判断
		if ($admin['password'] != $admin['confirm_password']) {
			return array('error' => '两次密码不一致！');
		}

		$data = array(
			'password'		 => md5($admin['password']),
			'modify_time'	 => date('Y-m-d H:i:s'),
		);

		// 修改
		$admin_id = $this->session->userdata('MSADMIN_ID');
		$res = $this->db->where('admin_id', $admin_id)->update('admin', $data);
		if ($res === false) {
			return array('error' => '密码修改失败！');
		}

		// 添加log
		$this->load_model('logs_model')->add_content("修改密码");

		return array('message' => '操作成功！');
	}

}
