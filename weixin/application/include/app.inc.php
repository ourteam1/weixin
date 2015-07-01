<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

// 加载公共文件
include __DIR__ . '/common.inc.php';

class App {

    private $_data = array();

    function __construct() {

    }

    /**
     * 魔术函数，动态获取变量
     */
    function __get($name) {
        if (!isset($this->_data[$name])) {
            $this->_data[$name] = call_user_func(array($this, "load_$name"));
        }
        return $this->_data[$name];
    }

    /**
     * 魔术函数，动态调用方法
     */
    function __call($name, $arguments) {
        return call_user_func_array(array($this, $name), $arguments);
    }

    /**
     * 程序入口
     */
    static function run() {
        // 加载公共文件
        include __DIR__ . '/route.inc.php';

        // 定义action class
        $class_name = ucfirst(__MODULE__) . ucfirst(__ACTION__);

        // 定义action args
        $args = array_slice($path, 3);

        // 定义action文件
        $action_file = MODULE_DIR . __MODULE__ . '/' . __ACTION__ . '.act.php';

        // 如果action文件不存在
        if (!file_exists($action_file)) {
            exit('no view!');
        }

        // 加载action基类
        include_once INCLUDE_DIR . 'action.inc.php';

        // 加载action文件
        include $action_file;

        // 调用action方法
        call_user_func_array(array(new $class_name(), 'on_' . __ACTION__), $args);
    }

}
