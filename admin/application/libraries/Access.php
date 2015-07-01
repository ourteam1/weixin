<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Access {

	/**
	 * 构造函数
	 */
	function __construct() {

	}

	/**
	 * 继承CI的属性
	 */
	function __get($key) {
		$CI = & get_instance();
		return isset($CI->$key) ? $CI->$key : null;
	}

	/**
	 * 检测访问权限
	 */
	function check_access_action($action) {
		// 数组转换
		$action = is_array($action) ? $action : explode(',', $action);

		// 如果请求是不登录就允许访问，就return true
		if (array_intersect($action, $this->config->item('nologin_access_actions'))) {
			return true;
		}

		// 如果没有登录，就return false
		if (!$this->session->userdata('MSADMIN_ACCOUNT')) {
			return false;
		}

		// 定义管理员权限
		$admin_access_actions = $this->session->userdata('MSADMIN_ACCESS_ACTIONS');

		// 如果是超级管理员
		if ($admin_access_actions == '*') {
			return true;
		}

		// 登陆后，默认所有管理员可以访问的URI
		$common_access_actions = $this->config->item('common_access_actions');
		if (array_intersect($action, $common_access_actions)) {
			return true;
		}

		// 如果不在管理员权限范围之内，不允许访问
		$access_actions = $this->get_access_actions();
		if (!array_intersect($action, $access_actions)) {
			return false;
		}

		// 如果不在当前管理员权限范围之内，不允许访问
		if (!array_intersect($action, $admin_access_actions)) {
			return false;
		}

		return true;
	}

	/**
	 * 获取所有的权限action集合
	 */
	function get_access_actions() {
		if ($this->session->userdata('MS_ACCCESS_ACTIONS')) {
			return $this->session->userdata('MS_ACCCESS_ACTIONS');
		}

		$actions		 = array();
		$access_actions	 = $this->config->item('access_actions');
		foreach ($access_actions as $i) {
			$actions = array_merge($actions, $i['actions']);
		}
		$this->session->set_userdata('MS_ACCCESS_ACTIONS', $actions);
		return $actions;
	}

}
