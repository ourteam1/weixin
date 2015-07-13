<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

// 开启session
@session_start();

// 定义时区
date_default_timezone_set('Asia/Hong_Kong');

// 定义debug
if (!defined('MSDEBUG')) {
    define('MSDEBUG', true);
}

// 定义显示错误信息
if (MSDEBUG) {
    error_reporting(E_ALL);
}

// 定义application目录
if (!defined('APP_DIR')) {
    define('APP_DIR', dirname(__DIR__) . '/');
}

// 定义根目录
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(APP_DIR) . '/');
}

// 定义include目录
define('INCLUDE_DIR', APP_DIR . 'include/');

// 定义libs目录
define('LIBS_DIR', APP_DIR . 'libs/');

// 定义module目录
define('MODULE_DIR', APP_DIR . 'module/');

// 定义view目录
define('VIEW_DIR', APP_DIR . 'view/');

// 加载配置文件
include APP_DIR . 'config/config.php';

// 加载方法定义文件
include INCLUDE_DIR . 'function.inc.php';
