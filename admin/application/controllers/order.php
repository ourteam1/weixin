<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MS_Controller {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 订单列表
     */
    public function index($offset = 0) {
        $order = $this->input->post('Order');
        $list_data = $this->load_model('order_model')->get_order_list($offset, $order);
        $this->view->assign($list_data);
        $this->view->assign('Order', $order);
        $this->view->display('order/order_index.html');
    }

    /**
     * 今日订单
     */
    function today($offset = null) {
        $param = $this->input->post('Order');
        $param['start_time'] = date('Y-m-d');
        $param['end_time'] = date('Y-m-d', strtotime('+1 Day'));
        $list_data = $this->load_model('order_model')->get_order_list($offset, $param, site_url('order/today'));
        $this->view->assign($list_data);
        $this->view->assign('Order', $param);
        $this->view->display('order/order_today.html');
    }

    /**
     * 订单详情
     */
    function view($order_id = null) {
        if (strpos($_SERVER['HTTP_REFERER'], 'today') !== false) {
            $this->uri->rsegments[2] = 'today_view';
        }

        $order = $this->load_model("order_model")->get_order_by_id($order_id);
        if (isset($order['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->view->assign($order);
        $this->view->display("order/order_view.html");
    }

    /**
     * 确认订单
     */
    function confirm($order_id = null) {
        $res = $this->load_model('order_model')->update_order_status($order_id, 2);
        if (isset($order['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', '确认订单成功！');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * 出库发货
     */
    function shipment($order_id = null) {
        $res = $this->load_model('order_model')->update_order_status($order_id, 3);
        if (isset($order['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', '操作成功！');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * 用户已收货
     */
    function receipt($order_id = null) {
        $res = $this->load_model('order_model')->update_order_status($order_id, 4);
        if (isset($order['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', '操作成功！');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * 用户未收货
     */
    function reconfirm($order_id = null) {
        $res = $this->load_model('order_model')->update_order_status($order_id, 5);
        if (isset($order['error'])) {
            $this->session->set_flashdata('error_message', $res['error']);
        } else {
            $this->session->set_flashdata('success_message', '操作成功！');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

}
