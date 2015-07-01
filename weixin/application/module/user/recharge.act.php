<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 会员充值
 * http://host/weixin/test.php/user/recharge
 */
class UserRecharge extends Action {

	function __construct() {
		parent::__construct();

		$this->wxauth();
	}

	function on_recharge() {
		$this->view->display('user/user_recharge.html');
	}

}
