<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

// 不需要登录就可以访问的URI
$config['nologin_access_actions'] = array(
	'site/login',
	'site/logout',
);

// 登陆后，默认所有管理员可以访问的URI
$config['common_access_actions'] = array(
	'site/index',
);

// 登录后，受管理员权限限制的URI
$config['access_actions'] = array(
	'admin.add'				 => array('label' => '添加管理员', 'actions' => array('admin/add')),
	'admin.index'			 => array('label' => '管理员列表', 'actions' => array('admin/index')),
	'admin.update'			 => array('label' => '编辑管理员', 'actions' => array('admin/index', 'admin/update')),
	'admin.delete'			 => array('label' => '删除管理员', 'actions' => array('admin/index', 'admin/delete')),
	'logs.index'			 => array('label' => '系统日志查看', 'actions' => array('logs/index')),
	'category.add'			 => array('label' => '添加分类', 'actions' => array('category/add')),
	'category.index'		 => array('label' => '分类列表', 'actions' => array('category/index')),
	'category.update'		 => array('label' => '编辑分类', 'actions' => array('category/index', 'category/update')),
	'category.delete'		 => array('label' => '删除分类', 'actions' => array('category/index', 'category/delete')),
	'goods.add'				 => array('label' => '添加商品', 'actions' => array('goods/add')),
	'goods.index'			 => array('label' => '商品列表', 'actions' => array('goods/index')),
	'goods.update'			 => array('label' => '编辑商品', 'actions' => array('goods/index', 'goods/update')),
	'goods.delete'			 => array('label' => '删除商品', 'actions' => array('goods/index', 'goods/delete')),
	'goods.grounding'		 => array('label' => '商品上架', 'actions' => array('goods/index', 'goods/grounding')),
	'goods.undercarriage'	 => array('label' => '商品下架', 'actions' => array('goods/index', 'goods/undercarriage')),
	'order.index'			 => array('label' => '订单列表', 'actions' => array('order/index')),
	'order.today'			 => array('label' => '今日订单', 'actions' => array('order/today')),
	'order.view'			 => array('label' => '订单详情', 'actions' => array('order/index', 'order/today', 'order/view')),
	'order.confirm'			 => array('label' => '确认订单', 'actions' => array('order/index', 'order/today', 'order/confirm')),
	'order.shipment'		 => array('label' => '出库发货', 'actions' => array('order/index', 'order/today', 'order/shipment')),
	'order.receipt'			 => array('label' => '用户已收货', 'actions' => array('order/index', 'order/today', 'order/receipt')),
	'user.index'			 => array('label' => '用户列表', 'actions' => array('user/index')),
	'user.view'				 => array('label' => '用户详情', 'actions' => array('user/index', 'user/view')),
	'user.account_details'	 => array('label' => '用户账户详情', 'actions' => array('user/index', 'user/account_details')),
	'user.recharge'			 => array('label' => '充值', 'actions' => array('user/index', 'user/recharge')),
	'message.index'			 => array('label' => '微信消息列表', 'actions' => array('message.index')),
	'message.send'			 => array('label' => '发送微信消息', 'actions' => array('message.send')),
);

// 管理员权限分组
$config['group_actions'] = array(
	'管理员管理'	 => array('admin.add', 'admin.index', 'admin.update', 'admin.delete', 'logs.index'),
	'商品管理'	 => array(
		'category.add', 'category.index', 'category.update', 'category.delete',
		'goods.add', 'goods.index', 'goods.update', 'goods.delete', 'goods.grounding', 'goods.undercarriage'),
	'订单管理'	 => array('order.index', 'order.confirm', 'order.shipment', 'order.receipt'),
	'用户管理'	 => array('user.index', 'user.view', 'user.recharge', 'user.account_details'),
//	'微信消息'	 => array('message.index', 'message.send'),
);

// 定义active nav menu
$config['set_active_nav'] = array(
	'首页'	 => array(
		'uri'			 => 'site/index',
		'active_actions' => array('site/index'),
	),
	'商品管理'	 => array(
		'uri'			 => 'goods/add,goods/index,category/add,category/index',
		'active_actions' => array('category/*', 'goods/*'),
		'active_menu'	 => array(
			array(
				'label'		 => '分类',
				'class'		 => 'glyphicon-th-large',
				'children'	 => array(
					array('label' => '添加分类', 'url' => 'category/add', 'active_actions' => array('category/add')),
					array('label' => '分类列表', 'url' => 'category/index', 'active_actions' => array('category/index', 'category/update', 'category/delete')),
				)
			),
			array(
				'label'		 => '商品',
				'class'		 => 'glyphicon-th-large',
				'children'	 => array(
					array('label' => '添加商品', 'url' => 'goods/add', 'active_actions' => array('goods/add')),
					array('label' => '商品列表', 'url' => 'goods/index', 'active_actions' => array('goods/index', 'goods/update', 'goods/delete', 'goods.grounding', 'goods.undercarriage')),
				)
			),
		),
	),
	'订单管理'	 => array(
		'uri'			 => 'order/today,order/index',
		'active_actions' => array('order/*'),
		'active_menu'	 => array(
			array(
				'label'		 => '订单',
				'class'		 => 'glyphicon-th-large',
				'children'	 => array(
					array('label' => '今日订单', 'url' => 'order/today', 'active_actions' => array('order/today', 'order/today_view')),
					array('label' => '订单列表', 'url' => 'order/index', 'active_actions' => array('order/index', 'order/view')),
				)
			),
		),
	),
	'用户管理'	 => array(
		'uri'			 => 'user/index',
		'active_actions' => array('user/*'),
		'active_menu'	 => array(
			array(
				'label'		 => '用户',
				'class'		 => 'glyphicon-th-large',
				'children'	 => array(
					array('label' => '用户列表', 'url' => 'user/index', 'active_actions' => array('user/index', 'user/view', 'user/recharge', 'user/account_details')),
				)
			),
		),
	),
//	'微信消息'	 => array(
//		'uri'			 => 'message/send,message/index',
//		'active_actions' => array('message/*'),
//		'active_menu'	 => array(
//			array(
//				'label'		 => '用户',
//				'class'		 => 'glyphicon-th-large',
//				'children'	 => array(
//					array('label' => '发送消息', 'url' => 'message/send', 'active_actions' => array('message/send')),
//					array('label' => '消息列表', 'url' => 'message/index', 'active_actions' => array('message/index')),
//				)
//			),
//		),
//	),
	'系统管理'	 => array(
		'uri'			 => 'admin/add,admin/index,logs/index',
		'active_actions' => array('admin/*', 'logs/*'),
		'active_menu'	 => array(
			array(
				'label'		 => '管理员',
				'class'		 => 'glyphicon-th-large',
				'children'	 => array(
					array('label' => '添加管理员', 'url' => 'admin/add', 'active_actions' => array('admin/add')),
					array('label' => '管理员管理', 'url' => 'admin/index', 'active_actions' => array('admin/index', 'admin/update', 'admin/delete')),
				)
			),
			array(
				'label'		 => '日志',
				'class'		 => 'glyphicon-th-large',
				'children'	 => array(
					array('label' => '系统日志', 'url' => 'logs/index', 'active_actions' => array('logs/index')),
				)
			),
		),
	),
);
