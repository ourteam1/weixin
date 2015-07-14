<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Focus_model extends MS_Model {

	function __construct() {
		parent::__construct();
	}

	/**
	 * 关注列表
	 */
	function get_focus_list() {
		$list_data = $this->db->where('status', 1)->order_by('sort', 'asc')->get('focus')->result();
		return array('list_data' => $list_data);
	}

	/**
	 * 关注详情
	 */
	function get_focus_by_id($focus_id) {
		$this->db->where('focus_id', $focus_id);
		return $this->db->where('status', 1)->get('focus')->row_array();
	}

    /**
	 * 关注最大sort
	 */
	function get_max_sort() {
		$arr = $this->db->select('max(sort) as sort')->get('focus')->row_array();
		return isset($arr['sort']) ? $arr['sort'] : 0;
	}

	/**
	 * 添加关注
	 */
	function add_focus($focus) {
		$sort  	 = trim(element('sort', $focus));
		$name    = trim(element('name', $focus));
		$score   = trim(element('score', $focus));
        $icon    = trim(element('icon', $focus));
        $url     = trim(element('url', $focus));

		// 判断
		if (!$name) {
			return array('error_code' => '10010', 'error' => '关注名称不能为空！');
		}

		// 判断
		$count = $this->db->where('status', 1)->where('name', $name)->count_all_results("focus");
		if ($count > 0) {
			return array('error_code' => '10011', 'error' => '关注名称已经存在！');
		}

		// 整理数据
		$data = array(
			'status'        => 1,
			'sort'          => intval($sort),
			'name'          => $name,
			'score'			=> $score,
            'icon'          => $icon,
            'url'           => $url,
            'create_time'   => date('Y-m-d H:i:s')
		);

		// 保存数据
		$res = $this->db->insert('focus', $data);
		if ($res === false) {
			return array('error_code' => '10012', 'error' => '添加关注失败！');
		}

		//写日志
		$this->load_model('logs_model')->add_content("添加关注： " . $data['name']);
		return array('message' => '添加关注成功！');
	}

	/**
	 * 更新关注
	 */
	function update_focus($focus_id, $focus) {
		$sort  	 = trim(element('sort', $focus));
		$name    = trim(element('name', $focus));
		$score   = trim(element('score', $focus));
        $icon    = trim(element('icon', $focus));
        $url     = trim(element('url', $focus));

		// 判断
		if (!$name) {
			return array('error_code' => '10010', 'error' => '关注名称不能为空！');
		}

		// 判断
		$this->db->where('focus_id !=', $focus_id)->where('name', $name);
		$count = $this->db->where('status', 1)->count_all_results("focus");
		if ($count > 0) {
			return array('error_code' => '10011', 'error' => '关注名称已经存在！');
		}

		// 整理数据
		$data = array(
			'sort'	=> intval($sort),
			'name'	=> $name,
			'score' => $score,
            'icon'  => $icon,
            'url'   => $url,
		);

		// 更新数据
		$res = $this->db->where('focus_id', $focus_id)->update('focus', $data);
		if ($res === false) {
			return array('error_code' => '10012', 'error' => '修改关注失败！');
		}

		// 写日志
		$this->load_model('logs_model')->add_content("修改关注： [{$focus_name}]");
		return array('message' => '修改关注成功！');
	}

	/**
	 * 删除关注
	 */
	function delete_focus($focus_id) {
		// 判断
		if (!$focus_id) {
			return array('error_code' => '10010', 'error' => '请选择要删除的关注!');
		}

		// 整理数据
		$data = array(
			'status' => 0,
		);

		// 修改状态
		$res = $this->db->where('focus_id', $focus_id)->update('focus', $data);
		if ($res === false) {
			return array('error_code' => '10012', 'eror' => '删除关注失败！');
		}

		// 添加log
		$this->load_model('logs_model')->add_content("删除关注：" . $focus_id);
		return array('message' => '删除关注成功！');
	}

}
