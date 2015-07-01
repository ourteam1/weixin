<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends MS_Controller {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 系统日志
     */
    public function index($offset = 0) {
        $list_data = $this->load_model('logs_model')->get_logs_list($offset);
        $this->view->assign($list_data);
        $this->view->display('system/logs_index.html');
    }

}
