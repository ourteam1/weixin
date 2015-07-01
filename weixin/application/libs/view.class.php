<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

class View {

    var $_layout    = 'layout/layout.html';
    var $_view_data = array();

    /**
     * 构造函数
     */
    function __construct() {
        
    }

    /**
     * 设置layout
     */
    function set_layout($layout) {
        $this->_layout = $layout;
        return $this;
    }

    /**
     * 设置参数
     */
    function assign($name, $value = '') {
        if (is_array($name) && $name) {
            $this->_view_data = array_merge($name, $this->_view_data);
        }
        else {
            $this->_view_data[$name] = $value;
        }
        return $this;
    }

    /**
     * 显示页面
     */
    function display($template_file) {
        $content = $this->fetch($template_file);

        if (trim($this->_layout) && file_exists(VIEW_DIR . $this->_layout)) {
            $this->assign('__content__', $content);
            $content = $this->fetch($this->_layout);
        }

        echo $content;
    }

    /**
     * 获取视图内容
     */
    function fetch($template_file) {
        @extract($this->_view_data);

        @ob_start();
        include VIEW_DIR . $template_file;
        $content = ob_get_contents();
        @ob_end_clean();

        $this->_view_data = array();
        unset($this->_view_data);

        return $content;
    }

}
