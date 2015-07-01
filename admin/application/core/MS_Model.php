<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class MS_Model extends CI_Model {

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 集成CI控制器的方法
     */
    public function __call($name, $arguments) {
        $CI = & get_instance();
        return call_user_func_array(array($CI, $name), $arguments);
    }

}
