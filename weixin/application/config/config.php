<?php
if (!defined('IN_MSAPP')) exit('Access Deny!');

define('WX_TOKEN', 'wx_wanzhuanhua');
define('WX_APPID', 'wx22790e761be32dcc');
define('WX_APPSECRET', '4aa97dba6bcf5e00189c69dc135ed8e3');

define('DB_DSN', 'mysql:dbname=wanzhuanhua;host=127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_TABLE_PREFIX', 'ms_');

define('MEMCACHE_ID', 'wanzhuanhua');
define('MEMCACHE_HOST', '127.0.0.1');
define('MEMCACHE_PORT', '11211');

// 图片显示地址
define('IMAGED', 'http://115.28.80.161/wutong/wzh/upload/data/');
