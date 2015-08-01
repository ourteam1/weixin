<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages_model extends MS_Model
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 公司详情
     */
    public function get_company()
    {
        return $company = $this->db->where(array('page_id' => 1))->get('pages')->row();
    }

    /**
     * 编辑公司详情
     */
    public function update_company($company)
    {
        $data = array(
            'title'       => empty($company['title']) ? '' : $company['title'],
            'image'       => empty($company['image']) ? '' : $company['image'],
            'thumb'       => empty($company['thumb']) ? '' : $company['thumb'],
            'content'     => empty($company['content']) ? '' : $company['content'],
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $company = $this->db->where(array('page_id' => 1))->update('pages', $data);
        if ($res === false) {
            return array('error' => 1, 'error_message' => '编辑公司信息失败');
        }

        // 写日志
        $this->load_model('logs_model')->add_content("编辑公司信息");

        return array('success_message' => '编辑公司信息成功');
    }

}
