<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

// 定义baseurl
!defined('__BASEURL__') && define('__BASEURL__', base_url());

// 定义public、upload地址
!defined('__PUBLIC__') && define('__PUBLIC__', base_url('public') . '/');
!defined('__UPLOAD__') && define('__UPLOAD__', base_url('upload') . '/');

class View {

    var $_layout = 'layout/layout.html';
    var $_data  = array();

    /**
     * 构造函数
     */
    function __construct() {

    }

    /**
     * 继承CI的属性
     */
    function __get($key) {
        $CI = & get_instance();
        return isset($CI->$key) ? $CI->$key : null;
    }

    /**
     * 设置layout
     */
    function set_layout($layout) {
        $this->layout = $layout;
        return $this;
    }

    /**
     * 设置参数
     */
    function assign($name, $value = '') {
        if (is_array($name) && $name) {
            $this->_data = array_merge($name, $this->_data);
        }
        else {
            $this->_data[$name] = $value;
        }
        return $this;
    }

    /**
     * 显示页面
     */
    function display($tmpl, $data = array()) {
        $content = $this->fetch($tmpl, $data);
        $layout = trim($this->layout) ? $this->layout : $this->_layout;
        echo $this->fetch($layout, array('__content__' => $content));
    }

    /**
     * 获取视图内容
     */
    function fetch($tmpl, $data = array()) {
        if ($data) {
            $this->assign($data);
        }
        return $this->load->view($tmpl, $this->_data, true);
    }

}
