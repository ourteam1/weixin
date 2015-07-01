<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 删除菜单
 */
class WechatDelMenu extends Action {

	function on_delMenu() {
		$u = isset($_REQUEST['u']) ? $_REQUEST['u'] : '';
		if ($u != 'hnatao') {
			die('no access!');
		}

		// 获取菜单
		$res = $this->weixin->menu_del();
		die("菜单被删除：" . var_export($res, true));
	}

}
