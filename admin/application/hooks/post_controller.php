<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 验证访问权限
 */
function chk_post_access() {
	$ci		 = & get_instance();
	$action	 = $ci->uri->rsegments[1] . '/' . $ci->uri->rsegments[2];

	// 判断请求是否在不登录的情况下允许访问
	if (in_array($action, $ci->config->item('nologin_access_actions'))) {
		return;
	}

	// 判断有没有登录，处理没有登录时的非法请求
	if (!$ci->session->userdata('MSADMIN_ACCOUNT')) {
		if ($ci->input->get_post('ajax')) {
			die(json_encode(array('error_code' => 10010, 'error' => 'server_timeout')));
		}
		redirect("site/logout");
	}

	// 对于已经登录的管理员，检测访问权限
	if (!$ci->access->check_access_action($action)) {
		if ($ci->input->get_post('ajax')) {
			die('{"error":"no permission!"}');
		}
		die('no permission!');
	}
}
