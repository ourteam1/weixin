<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

define('WX_TOKEN', 'wx_udian');
define('WX_APPID', 'wx914fccaea5c41e32');
define('WX_APPSECRET', '1f95d04cd0c8e7fc75e49f8bb4d1cc7e');

define('DB_DSN', 'mysql:dbname=xiaoqu;host=127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_TABLE_PREFIX', 'ms_');

define('MEMCACHE_ID', 'xiaoqu');
define('MEMCACHE_HOST', '127.0.0.1');
define('MEMCACHE_PORT', '11211');

// 图片显示地址
define('IMAGED', 'http://image.udian2.com/');
