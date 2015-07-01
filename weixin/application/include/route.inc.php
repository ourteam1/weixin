<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

preg_match('/(.*)\/(.*)\.php\/?(.*)?/', $_SERVER['REQUEST_URI'], $match);

// 定义base path
define('__BASEPATH__', get_http_host() . (isset($match[1]) ? $match[1] . '/' : '/'));

// 定义public path
define('__PUBLIC__', __BASEPATH__ . 'public/');

// 定义upload path
define('__UPLOAD__', __BASEPATH__ . 'upload/');

// 定义base uri
define('__BASEURI__', isset($match[2]) ? __BASEPATH__ . $match[2] . '.php/' : '/index.php/');

// 解析path_info
$path = explode('/', $_SERVER['PATH_INFO']);

// 定义module
define('__MODULE__', isset($path[1]) && trim($path[1]) != '' ? $path[1] : 'default');

// 定义action
define('__ACTION__', isset($path[2]) && trim($path[2]) != '' ? $path[2] : 'index');
