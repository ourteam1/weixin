<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Category_model extends MS_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 商品分类列表
     */
    public function get_category_list()
    {
        $list_data = $this->db->where('status', 1)->order_by('location', 'asc')->get('category')->result();
        return array('list_data' => $list_data);
    }

    /**
     * 商品分类排序列表
     */
    public function get_category_order_by_location()
    {
        return $this->db->where('status', 1)->order_by('location', 'asc')->get('category')->result();
    }

    /**
     * 商品分类详情
     */
    public function get_category_by_id($category_id)
    {
        $this->db->where('category_id', $category_id);
        return $this->db->where('status', 1)->get('category')->row_array();
    }

    /**
     * 商品分类最大location
     */
    public function get_max_location()
    {
        $arr = $this->db->select('max(location) as location')->get('category')->row_array();
        return isset($arr['location']) ? $arr['location'] : 0;
    }

    /**
     * 添加商品分类
     */
    public function add_category($category)
    {
        $location      = trim(element('location', $category));
        $category_name = trim(element('category_name', $category));

        // 判断
        if (!$category_name) {
            return array('error_code' => '10010', 'error' => '商品分类名称不能为空！');
        }

        // 判断
        $count = $this->db->where('status', 1)->where('category_name', $category_name)->count_all_results("category");
        if ($count > 0) {
            return array('error_code' => '10011', 'error' => '商品分类名称已经存在！');
        }

        // 整理数据
        $data = array(
            'status'        => 1,
            'location'      => intval($location),
            'category_name' => $category_name,
        );

        // 保存数据
        $res = $this->db->insert('category', $data);
        if ($res === false) {
            return array('error_code' => '10012', 'error' => '添加商品分类失败！');
        }

        //写日志
        $this->load_model('logs_model')->add_content("添加商品分类： " . $data['category_name']);
        return array('message' => '添加商品分类成功！');
    }

    /**
     * 更新商品分类
     */
    public function update_category($category_id, $category)
    {
        $location      = trim(element('location', $category));
        $category_name = trim(element('category_name', $category));

        // 判断
        if (!$category_name) {
            return array('error_code' => '10010', 'error' => '商品分类名称不能为空！');
        }

        // 判断
        $this->db->where('category_id !=', $category_id)->where('category_name', $category_name);
        $count = $this->db->where('status', 1)->count_all_results("category");
        if ($count > 0) {
            return array('error_code' => '10011', 'error' => '商品分类名称已经存在！');
        }

        // 整理数据
        $data = array(
            'location'      => intval($location),
            'category_name' => $category_name,
        );

        // 更新数据
        $res = $this->db->where('category_id', $category_id)->update('category', $data);
        if ($res === false) {
            return array('error_code' => '10012', 'error' => '修改商品分类失败！');
        }

        // 写日志
        $this->load_model('logs_model')->add_content("修改商品分类： [{$category_name}]");
        return array('message' => '修改商品分类成功！');
    }

    /**
     * 删除分类
     */
    public function delete_category($category_id)
    {
        // 判断
        if (!$category_id) {
            return array('error_code' => '10010', 'error' => '请选择要删除的商品分类!');
        }

        // 整理数据
        $data = array(
            'status' => 0,
        );

        // 修改状态
        $res = $this->db->where('category_id', $category_id)->update('category', $data);
        if ($res === false) {
            return array('error_code' => '10012', 'eror' => '删除商品分类失败！');
        }

        // 添加log
        $this->load_model('logs_model')->add_content("删除商品分类");
        return array('message' => '删除商品分类成功！');
    }

}
