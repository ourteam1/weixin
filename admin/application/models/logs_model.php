<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logs_model extends MS_Model {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 分页列表
     */
    function get_logs_list($offset = 0) {
        // 计算总数
        $count = $this->db->get('logs')->num_rows();

        // 条件、排序
        $this->db->order_by('logs_id', 'desc');

        // 分页查询数据
        $this->db->limit(PAGE_LIMIT, $offset);
        $list_data = $this->db->get('logs')->result();

        // 分页
        $this->pagination->set_base_url(site_url('logs/index'));
        $this->pagination->set_total_rows($count);
        $pagination = $this->pagination->get_links();

        return array('list_data' => $list_data, 'count' => $count, 'pagination' => $pagination);
    }

    /**
     * 添加日志信息
     */
    function add_content($content) {
        if (!$this->session->userdata('MSADMIN_ACCOUNT')) {
            return true;
        }
        $data = array(
            'username' => $this->session->userdata('MSADMIN_ACCOUNT'),
            'content'  => $content,
            'create_time' => date('Y-m-d H:i:s')
        );
        $res  = $this->db->insert('logs', $data);
        return $res ? $this->db->insert_id() : false;
    }

}
