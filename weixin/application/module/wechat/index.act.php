<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

define('SUBSCRIBE_INFOMATION', '亲爱的，感谢您关注小区菜园，我们为您准备了很多惊喜哦！您想为爱人准备一份精美的晚餐吗？您想吃到可口、健康的晚餐吗？我们为您提供了精选的食材，您只需要按照我们提供的菜谱炒一下就可以了。亲，还等什么，赶紧抢购吧！');

/**
 * 微信接口
 * http://host/weixin/index.php/weixin
 */
class WechatIndex extends Action {

	/**
	 * 程序入口
	 */
	function on_index() {
		$method = $this->weixin->get_rev_message();
		if (method_exists($this, $method)) {
			$this->$method();
		}
	}

	/**
	 * 我要充值
	 */
	function _click_i_will_recharge() {
		$news_data = array(
			array(
				'title'			 => '我要充值',
				'description'	 => '充值有惊喜，精选食材供您选哦！',
				'picurl'		 => __PUBLIC__ . 'image/recharge.jpg',
				'url'			 => site_url('user/recharge'),
			),
		);
		$this->weixin->reply_news($news_data);
	}

	/**
	 * 账户余额
	 */
	function _click_my_account() {
		$wx_openid	 = $this->weixin->get_rev_from();
		$user		 = $this->db->where('wx_openid', $wx_openid)->row('user');
		$text_data	 = "您好，" . $user['nickname'] . "！您的账户余额：￥" . $user['amount'];
		$this->weixin->reply_text($text_data);
	}

	/**
	 * 关注
	 */
	function _subscribe() {
		$userinfo = $this->weixin->get_userinfo();

		logger("关注者信息：", __FILE__, __LINE__);
		logger(var_export($userinfo, true), __FILE__, __LINE__);

		$user = $this->db->where('wx_openid', $userinfo['openid'])->row('user');
		if (!$user) {
			$data = array(
				'wx_openid'		 => $userinfo['openid'],
				'nickname'		 => $userinfo['nickname'],
				'sex'			 => $userinfo['sex'],
				'city'			 => $userinfo['city'],
				'province'		 => $userinfo['province'],
				'country'		 => $userinfo['country'],
				'headimgurl'	 => $userinfo['headimgurl'],
				'create_time'	 => date('Y-m-d H:i:s'),
				'modify_time'	 => date('Y-m-d H:i:s'),
			);
			$this->db->insert('user', $data);
		}

		$news_data = array(
			array(
				'title'			 => '亲爱的，感谢您关注小区菜园，我们为您准备了很多惊喜哦！',
				'description'	 => SUBSCRIBE_INFOMATION,
				'picurl'		 => __PUBLIC__ . 'image/subscribe.jpg',
				'url'			 => site_url('goods/index'),
			),
		);
		$this->weixin->reply_news($news_data);
	}

}
