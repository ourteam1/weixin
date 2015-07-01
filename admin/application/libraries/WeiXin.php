<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!class_exists('WeiXin')) {
	include APPPATH . 'libraries/weixin.class.php';
}

class MS_WeiXin extends WeiXin {

	/**
	 * 构造函数
	 */
	function __construct() {
		$options = array(
			'token'			 => WX_TOKEN,
			'appid'			 => WX_APPID,
			'appsecret'		 => WX_APPSECRET,
			'log_callback'	 => 'debug_message',
			'debug'			 => true,
		);

		parent::__construct($options);
	}

}
