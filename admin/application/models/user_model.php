<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MS_Model {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 分页列表
	 *
	 * @param type $offset 偏移量
	 * @param type $where 条件
	 * @return type
	 */
	function get_user_list($offset = 0, $where = array()) {
		$nickname	 = element('nickname', $where);
		$sex		 = element('sex', $where);

		// 总数
		if (trim($sex) != '') {
			$this->db->where('sex', $sex);
		}
		if (trim($nickname) != '') {
			$this->db->like('nickname', $nickname);
		}
		$count = $this->db->count_all_results('user');

		// 条件、排序
		if (trim($sex) != '') {
			$this->db->where('sex', $sex);
		}
		if (trim($nickname) != '') {
			$this->db->like('nickname', $nickname);
		}
		$this->db->order_by('user_id', 'desc');

		// 分页查询数据
		$this->db->limit(PAGE_LIMIT, $offset);
		$list_data = $this->db->get('user')->result();

		// 分页
		$this->pagination->set_base_url(site_url('user/index'));
		$this->pagination->set_total_rows($count);
		$pagination = $this->pagination->get_links();

		return array('list_data' => $list_data, 'count' => $count, 'pagination' => $pagination);
	}

	/**
	 * 查询用户信息
	 */
	function get_user_by_id($user_id = 0) {
		return $this->db->where('user_id', $user_id)->get('user')->row_array();
	}

	/**
	 * 用户充值
	 */
	function recharge($user_id, $money) {
		// 开启事务
		$this->db->trans_begin();

		// 判断
		$user = $this->get_user_by_id($user_id);
		if (!$user) {
			return array('error' => '用户不存在！');
		}

		// 判断
		$money = intval($money);
		if (!$money) {
			return array('error' => '请填写正确的充值金额！');
		}

		// 更新用户金额
		$amount	 = intval($user['amount'] + $money);
		$data	 = array(
			'amount'		 => $amount,
			'modify_time'	 => date('Y-m-d H:i:s'),
		);
		$res	 = $this->db->where('user_id', $user_id)->update('user', $data);
		if ($res === false) {
			$this->db->trans_rollback(); // 回滚事务
			return array('error' => '充值失败！');
		}

		// 记录充值过程
		$data	 = array(
			'user_id'		 => $user_id,
			'action'		 => 'user.recharge',
			'action_name'	 => '充值',
			'amount'		 => $money,
			'create_time'	 => date('Y-m-d H:i:s'),
		);
		$res	 = $this->db->insert('user_account', $data);
		if ($res === false) {
			$this->db->trans_rollback(); // 回滚事务
			return array('error' => '记录充值信息失败！');
		}

		// 提交事务
		$this->db->trans_commit();

		// 记录日志
		$this->load_model('logs_model')->add_content("给用户{$user['nickname']}[编号={$user_id}]充值{$money}");

		// 发送客服消息
		if (isset($user['wx_openid'])) {
			$this->weixin->custom_text($user['wx_openid'], $user['nickname'] . "您好，系统为您充值{$money}元，您的账户余额是{$amount}元");
		}

		return array('message' => 'ok');
	}

	/**
	 * 账户明细
	 */
	function get_user_account_details($user_id, $offset = 0) {
		// 总数
		$this->db->where('user_id', $user_id);
		$count = $this->db->count_all_results('user_account');

		// 条件、排序
		$this->db->where('user_id', $user_id);
		$this->db->order_by('create_time', 'desc');

		// 分页查询数据
		$this->db->limit(PAGE_LIMIT, $offset);
		$list_data = $this->db->get('user_account')->result();

		// 分页
		$this->pagination->set_base_url(site_url('user/account_details/' . $user_id));
		$this->pagination->set_total_rows($count);
		$pagination = $this->pagination->get_links();

		return array('list_data' => $list_data, 'count' => $count, 'pagination' => $pagination);
	}

}
