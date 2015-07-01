<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message_model extends MS_Model {

	protected $message_filed = array(
		'seller'		 => '发送商户',
		'title'			 => '标题',
		'image_all_path' => '封面',
		'status_text'	 => '状态',
		'send_time'		 => '发送时间',
	);

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 分页列表
	 */
	function get_list_data($offset = 0) {
		// 计算总数
		$count = $this->db->get('order')->num_rows();

		// 排序
		$this->db->order_by('create_time ', 'desc');

		// 分页查询数据
		$this->db->limit(PAGE_LIMIT, $offset);
		$list_data = $this->db->get(' message')->result_array();

		//数据整理
		if ($list_data) {
			foreach ($list_data as $k => $message) {
				//处理图片
				if (isset($message['image_path']) && trim($message['image_path'])) {
					$message['image_all_path'] = site_url('image/index?src = ' . $message['image_path']);
				}
				//处理状态
				if (isset($message['status'])) {
					$message['status_text'] = trim($message['status']) ? '已发送' : '未发送';
				}
				//处理发送时间
				if (isset($message['send_time']) && trim($message['send_time'])) {
					$message['send_time'] = date('Y-m-d', strtotime($message['send_time']));
				}

				$list_data[$k] = $message;
			}
		}

		// 分页
		$this->pagination->set_base_url(site_url('message/index'));
		$this->pagination->set_total_rows($count);
		$pagination = $this->pagination->get_links();

		return array('list_data' => $list_data, 'count' => $count, 'filed' => $this->message_filed, 'pagination' => $pagination);
	}

}
