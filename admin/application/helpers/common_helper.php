<?php

/**
 * 公共函数
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * json格式输出
 */
if (!function_exists('die_json')) {

    function die_json($arr = '') {
        $json_str = json_encode($arr, JSON_UNESCAPED_UNICODE);
        if (isset($_REQUEST['callback'])) {
            $cb = $_REQUEST['callback'];
            die($cb . "(" . $json_str . ")");
        }

        die($json_str);
    }

}

/**
 * 上传图片到远程服务器
 */
if (!function_exists('upload_image')) {

    function upload_image($url, $image) {
        $fields['upfile'] = '@' . $image;
        log_message('error', var_export($fields, true));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        if ($error = curl_error($ch)) {
            $res = null;
            log_message('error', var_export($error, true));
        }
        curl_close($ch);
        return $res;
    }

}

/**
 * 调试日志输出
 */
if (!function_exists('debug_message')) {

    function debug_message($message, $filename = null, $linenum = null) {
        static $_log;

        if (config_item('log_threshold') == 0) {
            return;
        }

        $_log = & load_class('Log');
        $_log->debug($message, $filename, $linenum);
    }

}
