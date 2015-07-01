<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 群发消息
 */
class WechatNews extends Action {

	function on_news() {
		$u = isset($_REQUEST['u']) ? $_REQUEST['u'] : '';
		if ($u != 'hnatao') {
			die('no access!');
		}

		$articles = array(
			array(
				"title"			 => "天天有优惠，单单有惊喜",
				"description"	 => "亲爱的，感谢您关注小区菜园，我们为您准备了很多惊喜哦！您想为爱人准备一份精美的晚餐吗？您想吃到可口、健康的晚餐吗？我们为您提供了精选的食材，您只需要按照我们提供的菜谱炒一下就可以了。亲，还等什么，赶紧抢购吧！",
				"url"			 => site_url('goods/index'),
				"picurl"		 => __PUBLIC__ . "image/goods.jpg"
			),
			array(
				'title'			 => '会员充值',
				'description'	 => '充值有惊喜，精选食材供您选哦！',
				'url'			 => site_url('user/recharge'),
				'picurl'		 => __PUBLIC__ . 'image/recharge.jpg',
			),
			array(
				'title'			 => '订单管理',
				'description'	 => '随时随地跟踪订单，查看怎么制作一份精美的晚餐！',
				'url'			 => site_url('order/index'),
				'picurl'		 => __PUBLIC__ . 'image/order.jpg',
			),
			array(
				'title'			 => '意见反馈',
				'description'	 => '您宝贵的建议是我们前进并提供更优质服务的动力！',
				'url'			 => site_url('feedback/index'),
				'picurl'		 => __PUBLIC__ . 'image/feedback.jpg',
			),
		);

		$users = $this->db->where('wx_openid', '!=', '')->result('user');
		foreach ($users as $user) {
			$this->weixin->custom_news($user['wx_openid'], $articles);
		}

		die('ok!');
	}

}
