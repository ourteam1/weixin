<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message extends MS_Controller {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 消息列表
	 */
	public function index($offset = 0) {
		$today_message = $this->load_model('message_model')->get_today_message();
		$this->view->assign($today_message);
		$this->view->display('message/message_index.html');
	}

	/**
	 * 发送消息
	 */
	public function send() {
		$this->view->display('message/message_send.html');
	}

}
