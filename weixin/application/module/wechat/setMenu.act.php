<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 设置菜单
 */
class WechatSetMenu extends Action {

	function on_setMenu() {
		$u = isset($_REQUEST['u']) ? $_REQUEST['u'] : '';
		if ($u != 'hnatao') {
			die('no access!');
		}

		// 获取菜单
		$res = $this->weixin->menu_get();

		if (isset($res['menu']) && count($res['menu']['button']) > 0) {
			die("菜单已经设置：" . var_export($res, true));
		}

		// 设置菜单
		$newmenu = array(
			"button" => array(
				array('type' => 'view', 'name' => '开始下单', 'url' => 'http://wx.udian2.com/index.php/goods/'),
				array('name'		 => '我是会员', "sub_button" => array(
						array("type" => "view", "name" => "我的订单", "url" => "http://wx.udian2.com/index.php/order/"),
						array("type" => "click", "name" => "我要充值", "key" => "I_WILL_RECHARGE"),
						array("type" => "click", "name" => "账户余额", "key" => "MY_ACCOUNT"),
					)),
				array('name'		 => '关于我们', "sub_button" => array(
						array("type" => "view", "name" => "关于我们", "url" => "http://wx.udian2.com/index.php/goods/"),
						array("type" => "view", "name" => "反馈意见", "url" => "http://wx.udian2.com/index.php/feedback/"),
					)),
			)
		);

		$res = $this->weixin->menu_create($newmenu);
		die("设置菜单：" . ($res ? '成功！' : '失败！'));
	}

}
