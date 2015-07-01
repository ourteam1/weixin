<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 提交购物车数据
 * http://host/weixin/index.php/goods/cart
 */
class GoodsCart extends Action {

	function __construct() {
		parent::__construct();

		$this->wxauth();
	}

	function on_cart() {
		$cart = $_REQUEST['cart'];

		// 判断
		if (!$cart) {
			die_json(array('error_code' => 10012, 'error' => '购物车加入失败，购物车数据为空！'));
		}

		// 启动事务
		$this->db->trans_start();

		// 删除购物车之前记录的数据
		$this->db->where('user_id', $this->user_id)->where('session_id', session_id());
		$res = $this->db->delete('cart');
		if ($res === false) {
			$this->db->trans_rollback(); // 回滚事务
			die_json(array('error_code' => 10013, 'error' => '购物车加入失败，删除数据失败！'));
		}

		// 添加购物车数据
		$flag = true;
		foreach ($cart as $i) {
			$data	 = array(
				'user_id'		 => $this->user_id,
				'session_id'	 => session_id(),
				'goods_id'		 => $i['goods_id'],
				'goods_price'	 => $i['goods_price'],
				'cart_num'		 => $i['cart_num'],
				'create_time'	 => date('Y-m-d H:i:s'),
			);
			$res	 = $this->db->insert('cart', $data);
			if ($res === false) {
				$flag = true;
				break;
			}
		}

		// 判断
		if (!$flag) {
			$this->db->trans_rollback(); // 回滚事务
			die_json(array('error_code' => 10014, 'error' => '购物车加入失败！'));
		}

		// 提交事务
		$this->db->trans_commit();

		// 返回成功信息
		die_json(array('message' => 'ok.'));
	}

}
