<?php
// 定义项目入口
define('IN_MSAPP', 'weixin');

// 定义根目录
define('ROOT_DIR', __DIR__ . '/');

// 定义application目录
define('APP_DIR', ROOT_DIR . 'application/');
define('MSDEBUG', '333');
// 加载项目程序文件
include APP_DIR . '/include/app.inc.php';

// 执行项目
App::run();
