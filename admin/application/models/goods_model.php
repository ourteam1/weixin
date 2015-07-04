<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Goods_model extends MS_Model {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 查找商品
     */
    function get_goods_by_id($goods_id) {
        return $this->db->where('status !=', 0)->where('goods_id', $goods_id)->get('goods')->row_array();
    }

    /**
     * 分页列表
     */
    function get_list_data($offset = 0, $query = array()) {
        // 条件
        if (isset($query['category_id']) && trim($query['category_id']) != '') {
            $this->db->where('category_id ', $query['category_id']);
        }
        if (isset($query['status']) && trim($query['status']) != '') {
            $this->db->where('status ', $query['status']);
        }

        // 计算总数
        $count = $this->db->where('status !=', 0)->get('goods')->num_rows();

        // 条件
        if (isset($query['category_id']) && trim($query['category_id']) != '') {
            $this->db->where('category_id ', $query['category_id']);
        }
        if (isset($query['status']) && trim($query['status']) != '') {
            $this->db->where('status ', $query['status']);
        }

        // 排序
        $this->db->order_by('goods_id', 'desc');

        // 分页查询数据
        $this->db->limit(PAGE_LIMIT, $offset);
        $list_data = $this->db->where('status !=', 0)->get('goods')->result();

        // 分页
        $this->pagination->set_base_url(site_url('goods/index'));
        $this->pagination->set_total_rows($count);
        $pagination = $this->pagination->get_links();

        return array('list_data' => $list_data, 'count' => $count, 'pagination' => $pagination);
    }

    /**
     * 添加商品
     */
    function add($goods) {
        // 整理数据
        $data = array(
            'goods_name' => element('goods_name', $goods, ''),
            'goods_sn' => date('YmdHis') . mt_srand(),
            'image' => element('image', $goods, 'default.jpg'),
            'thumb' => element('thumb', $goods, 'default.jpg'),
            'price' => element('price', $goods, 0),
            'discount' => element('discount', $goods, 0),
            'score' => element('score', $goods, 0),
            'category_id' => element('category_id', $goods, 0),
            'desc' => element('desc', $goods, ''),
            'order_number' => 0,
            'status' => 1,
            'create_time' => date('Y-m-d H:i:s'),
            'modify_time' => date('Y-m-d H:i:s'),
        );

        // 判断
        if (trim($data['goods_name']) == '') {
            return array('error' => '商品名称不能为空');
        }

        // 添加
        $res = $this->db->insert('goods', $data);

        // 判断
        if ($res === false) {
            return array('error' => '添加商品失败！');
        }

        // 添加log
        $this->load_model('logs_model')->add_content("添加商品： [{$data['goods_name']}]");

        return array('message' => '添加商品成功！');
    }

    /**
     * 更新商品
     */
    function update($goods_id, $goods) {
        // 整理数据
        $data = array(
            'goods_name' => element('goods_name', $goods, ''),
            'price' => element('price', $goods, 0),
            'discount' => element('discount', $goods, 0),
            'score' => element('score', $goods, 0),
            'category_id' => element('category_id', $goods, 0),
            'desc' => element('desc', $goods, ''),
            'modify_time' => date('Y-m-d H:i:s'),
        );
        if (isset($goods['image']) && trim($goods['image']) != '') {
            $data['image'] = element('image', $goods, 'default.jpg');
        }
        if (isset($goods['thumb']) && trim($goods['thumb']) != '') {
            $data['thumb'] = element('thumb', $goods, 'default.jpg');
        }

        // 判断
        if (trim($data['goods_name']) == '') {
            return array('error' => '名称不能为空');
        }

        //判断菜单是否存在
        $goods_info = $this->db->where('status !=', 0)->where('goods_id', $goods_id)->get('goods')->row_array();
        if (!$goods_info) {
            return array('error' => '商品不存在！');
        }

        // 修改菜单信息
        $res = $this->db->where('status !=', 0)->where('goods_id', $goods_id)->update('goods', $data);
        if ($res === false) {
            return array('error' => '修改商品失败！');
        }

        // 写日志
        $this->load_model('logs_model')->add_content("修改商品：" . $data['goods_name']);
        return array('message' => '修改商品成功！');
    }

    /**
     * 删除商品
     */
    function delete_goods($goods_id) {
        $data = array(
            'status' => 0,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('status !=', 0)->where('goods_id', $goods_id)->update('goods', $data);
        if ($res === false) {
            return array('error' => '删除商品失败！');
        }

        // 写日志
        $this->load_model('logs_model')->add_content("删除商品：" . $goods_id);
        return array('message' => '删除商品成功！');
    }

    /**
     * 商品上架=1、下架=2
     */
    function update_goods_status($goods_id, $status) {
        $data = array(
            'status' => $status,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('status !=', 0)->where('goods_id', $goods_id)->update('goods', $data);
        if ($res === false) {
            return array('error' => '操作失败！');
        }

        // 写日志
        $this->load_model('logs_model')->add_content(($status == 1 ? '上架' : '下架') . "商品：" . $goods_id);
        return array('message' => '操作成功！');
    }

}
