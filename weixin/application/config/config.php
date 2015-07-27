<?php
if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

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

//短信配置
define('ACCOUNTSID', 'aaf98f89485af69701485badfd69002d');
define('ACCOUNTTOKEN', '640b4cf833e44def91289a1e4591a637');
define('APPID', 'aaf98f89485af69701485bae817f002f');
define('SERVERIP', 'sandboxapp.cloopen.com');
define('SERVERPORT', '8883');
define('TEMPID', '1');
define('SOFTVERSION', '2013-12-26');
