<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | custom message
  |--------------------------------------------------------------------------
 */

// 定义分页每页多少条
define('PAGE_LIMIT', 10);

// 图片上传地址
define('UPLOAD_IMAGE', 'http://image.udian2.com/upload');

// 图片显示地址
define('IMAGED', 'http://image.udian2.com/');

// 定义微信配置
define('WX_TOKEN', 'wx_udian');
define('WX_APPID', 'wx914fccaea5c41e32');
define('WX_APPSECRET', '1f95d04cd0c8e7fc75e49f8bb4d1cc7e');

// 定义 memcached 配置
define('MEMCACHE_ID', 'xiaoqu');
define('MEMCACHE_HOST', '127.0.0.1');
define('MEMCACHE_PORT', '11211');

/* End of file constants.php */
/* Location: ./application/config/constants.php */