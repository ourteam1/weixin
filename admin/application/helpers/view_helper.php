<?php

/**
 * 定义模版方法
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 检测权限
 */
if (!function_exists('chk_access')) {

	function chk_access($action) {
		$ci		 = & get_instance();
		$actions = explode(',', $action);
		return $ci->access->check_access_action($actions);
	}

}

/**
 * 设置导航
 */
if (!function_exists('set_active_nav()')) {

	function set_active_nav($label, $nav) {
		$ci = & get_instance();

		// 判断访问权限并定义nav的url
		$nav_url = '';
		$uri_arr = explode(',', $nav['uri']);
		foreach ($uri_arr as $uri) {
			if ($ci->access->check_access_action($uri)) {
				$nav_url = site_url(trim($uri));
				break;
			}
		}

		// 如果没有权限
		if (trim($nav_url) == '') {
			return '';
		}

		// 定义是否激活nav
		$active		 = '';
		$action_arr	 = array(
			$ci->uri->rsegments[1] . '/*',
			$ci->uri->rsegments[1] . '/' . $ci->uri->rsegments[2],
		);
		if (array_intersect($action_arr, $nav['active_actions'])) {
			$active		 = 'active';
			// 设置当前激活的菜单
			$ci->menu	 = isset($nav['active_menu']) ? $nav['active_menu'] : array();
		}

		// 返回数据
		$tpl = '<li class="%s"><a href="%s">%s</a></li>';
		return sprintf($tpl, $active, $nav_url, $label);
	}

}

/**
 * 设置菜单
 */
if (!function_exists('set_menu')) {

	function set_menu($label, $class = '', $children = array()) {
		$ci		 = & get_instance();
		$action	 = $ci->uri->rsegments[1] . '/' . $ci->uri->rsegments[2];

		// 如果没有子
		if (!isset($children)) {
			return '';
		}

		$dl_tpl	 = '<dl class="menu">%s</dl>';
		$dt_tpl	 = '<dt><span class="glyphicon %s"></span> <strong>%s</strong></dt>';
		$dd_tpl	 = '<dd class="%s"><a href="%s">%s</a></dd>';

		$dt	 = sprintf($dt_tpl, $class, $label);
		$dd	 = '';
		foreach ($children as $i) {
			if (!chk_access($i['url'])) {
				continue;
			}
			$active = '';
			if (in_array($action, $i['active_actions'])) {
				$active = 'active';
			}
			$dd .= sprintf($dd_tpl, $active, site_url(trim($i['url'])), $i['label']);
		}

		if ($dd == '') {
			return '';
		}

		return sprintf($dl_tpl, $dt . $dd);
	}

}

/**
 * 设置表单值
 */
if (!function_exists('set_val')) {

	function set_val($name = '', $default = '') {
		$ci		 = & get_instance();
		$data	 = $ci->view->_data;

		$flag	 = true;
		$arr	 = explode('.', $name);
		for ($i = 0; $i < count($arr); $i++) {
			$name = $arr[$i];
			if (!isset($data[$name])) {
				$flag = false;
				break;
			}
			$data = $data[$name];
		}
		if (!$flag) {
			return $default;
		}
		return $data;
	}

}
