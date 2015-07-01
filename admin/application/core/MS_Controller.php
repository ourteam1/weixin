<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class MS_Controller extends CI_Controller {

    var $layout = 'layout/layout_1.html';

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * 加载model对象
     */
    function load_model($model_name, $model_alias_name = '') {
        if (!file_exists(APPPATH . 'models/' . $model_name . '.php')) {
            return null;
        }
        if (trim($model_alias_name) == '') {
            $model_alias_name = str_replace('\/', '_', $model_name);
        }
        $this->load->model($model_name, $model_alias_name);
        return $this->$model_alias_name;
    }

}
