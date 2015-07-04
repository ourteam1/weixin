<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends MS_Model {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 订单列表
     *
     * @param type $offset
     * @return type
     */
    function get_order_list($offset = 0, $where = array(), $baseurl = '') {
        // 总数
        element('is_pay', $where, '') !== '' && $this->db->where('is_pay', $where['is_pay']);
        element('order_code', $where, '') !== '' && $this->db->like('order_code', $where['order_code']);
        element('start_time', $where) && $this->db->where('order.create_time >=', $where['start_time']);
        element('end_time', $where) && $this->db->where('order.create_time <=', $where['end_time']);
        $count = $this->db->count_all_results('order');

        // 条件、排序
        element('is_pay', $where, '') !== '' && $this->db->where('is_pay', $where['is_pay']);
        element('order_code', $where, '') !== '' && $this->db->like('order_code', $where['order_code']);
        element('start_time', $where) && $this->db->where('order.create_time >=', $where['start_time']);
        element('end_time', $where) && $this->db->where('order.create_time <=', $where['end_time']);
        $this->db->order_by('order.create_time', 'DESC');

        // 分页查询数据
        $this->db->limit(PAGE_LIMIT, $offset);
        $list_data = $this->db->get('order')->result();

        // 查询property
        $property_data = array();
        $property = $this->db->like('name', 'order.action.', 'after')->get('property')->result();
        foreach ($property as $i) {
            $property_data[$i->value] = $i->label;
        }

        // 整合数据
        foreach ($list_data as $k => $i) {
            $i->status_text = $property_data[$i->status];
            $list_data[$k] = $i;
        }

        // 分页
        $this->pagination->set_base_url(trim($baseurl) != '' ? $baseurl : site_url('order/index'));
        $this->pagination->set_total_rows($count);
        $pagination = $this->pagination->get_links();

        return array('list_data' => $list_data, 'count' => $count, 'pagination' => $pagination);
    }

    /**
     * 获取订单信息
     */
    function get_order_by_id($order_id) {
        if (!$order_id) {
            return array("error_code" => "10010", "error" => "请选择查看的订单！");
        }

        // 订单信息
        $order = $this->db->where('order_id', $order_id)->get("order")->row_array();
        if (!$order) {
            return array('error_code' => '10011', 'error' => '查看订单不存在！');
        }

        // 查询property
        $property_info = $this->db->like('name', 'order.action.', 'after')->where('value', $order['status'])->get('property')->row_array();
        $order['status_text'] = $property_info['label'];

        // 订单商品
        $order['goods'] = $this->db->join('goods', 'order_info.goods_id=goods.goods_id')->where('order_info.order_id', $order_id)->get('order_info')->result_array();

        // 计算总数、总价钱
        $order['total_cart_num'] = 0;
        $order['total_goods_price'] = 0;
        $order['total_goods_score'] = 0;
        foreach ($order['goods'] as $i) {
            $order['total_cart_num'] += $i['cart_num'];
            $order['total_goods_price'] += $i['goods_price'];
            $order['total_goods_score'] += $i['goods_score'] * $i['cart_num'];
        }

        // 订单跟踪
        $order['order_action'] = $this->db->where('order_id', $order_id)->get('order_action')->result_array();

        return $order;
    }

    /**
     * 修改订单状态
     */
    function update_order_status($order_id, $status) {
        if (!$order_id) {
            return array('error_code' => '10011', 'error' => '错误的订单号！');
        }

        $order_info = $this->db->where('order_id', $order_id)->get('order')->row_array();
        if (!$order_info) {
            return array('error_code' => '10011', 'error' => '订单号不存在！');
        }

        $property_info = $this->db->like('name', 'order.action.', 'after')->where('value', $status)->get('property')->row_array();
        if (!$property_info) {
            return array('error_code' => '10011', 'error' => '错误的状态值！');
        }

        // 开启事务
        $this->db->trans_start();

        // 更新订单状态
        $data = array(
            'status' => $status,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('order_id', $order_id)->update('order', $data);
        if ($res === false) {
            // 回滚事务
            $this->db->trans_rollback();
            return array('error_code' => '10011', 'error' => '操作失败！');
        }

        // 订单操作明细
        $data = array(
            'order_id' => $order_id,
            'action' => $property_info['name'],
            'action_name' => $property_info['label'],
            'create_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->insert('order_action', $data);
        if ($res === false) {
            // 回滚事务
            $this->db->trans_rollback();
            return array('error_code' => '10011', 'error' => '记录订单操作明细失败！');
        }

        // 提交事务
        $this->db->trans_complete();

        // 设置日志
        $this->load_model('logs_model')->add_content('修改订单[编号：' . $order_id . ']状态：' . $property_info['label']);

        // 获取用户信息
        $user = $this->db->where('user_id', $order_info['user_id'])->get('user')->row_array();

        // 发送客服消息
        if (isset($user['wx_openid'])) {
            $this->weixin->custom_text($user['wx_openid'], $user['nickname'] . ' 您好，您的 ' . $order_info['order_sn'] . ' 订单 ' . $property_info['label']);
        }

        return array('message' => '操作成功！');
    }

}
