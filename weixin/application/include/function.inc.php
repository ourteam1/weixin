<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 获取get、post请求数据
 */
if (!function_exists('get_post')) {

	function get_post($val = '', $defv = '') {
		if ($_REQUEST) {
			foreach ($_REQUEST as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if (is_string($v2)) {
							$_REQUEST[$k][$k2] = trim($v2);
						}
					}
				}
				else if (is_string($v)) {
					$_REQUEST[$k] = trim($v);
				}
			}
		}

		if (trim($val) != '' && isset($_REQUEST[$val])) {
			return $_REQUEST[$val];
		}

		if (trim($val) != '') {
			return $defv;
		}

		return $_REQUEST;
	}

}

/**
 * 获取当前服务器的ip:port
 */
if (!function_exists('get_http_host')) {

	function get_http_host() {
		$host = '127.0.0.1';
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
			$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
		}
		else if (isset($_SERVER['HTTP_HOST'])) {
			$host = $_SERVER['HTTP_HOST'];
		}
		else if (isset($_SERVER['SERVER_NAME'])) {
			$host = $_SERVER['SERVER_NAME'];
		}
		else if (isset($_SERVER['SERVER_ADDR'])) {
			$host = $_SERVER['SERVER_ADDR'];
		}

		$port = $_SERVER['SERVER_PORT'];
		if (strpos($host, ':') === false && $port != 80) {
			$host = "{$host}:{$port}";
		}

		return 'http://' . $host;
	}

}

/**
 * 定义redirect方法
 */
if (!function_exists('redirect')) {

	function redirect($uri) {
		header('Location: ' . $uri);
		exit;
	}

}

/**
 * 定义action方法
 */
if (!function_exists('site_url')) {

	function site_url($uri) {
		return __BASEURI__ . $uri;
	}

}

/**
 * 日志记录
 */
if (!function_exists('logger')) {

	function logger($content, $filename = '', $linenum = '') {
		$log_dir = ROOT_DIR . 'logs/';

		if (!file_exists($log_dir)) {
			@mkdir($log_dir, 0777, 1);
		}

		$log_file = $log_dir . date('Ymd') . '.log';

		// 格式化输出内容
		$content = ">> " . date('Y-m-d H:i:s') . " >> $content --> " . $filename . " $linenum \r\n";

		if (is_writable($log_dir)) {
			@file_put_contents($log_file, $content, FILE_APPEND);
		}

		return true;
	}

}

/**
 * 定义die_json方法
 */
if (!function_exists('die_json')) {

	function die_json($arr) {
		@ob_clean();
		die(json_encode($arr, JSON_UNESCAPED_UNICODE));
	}

}

/**
 * 当系统出现严重错误时
 */
if (!function_exists('_shutdown')) {

	function _shutdown() {
		$err = error_get_last();

		if (!$err) {
			return false;
		}

		logger($err['message'], $err['file'], $err['line']);
		return true;
	}

	@register_shutdown_function('_shutdown');
}

/**
 * 当系统抛出异常时
 */
if (!function_exists('_exception_handler')) {

	function _exception_handler($severity, $message, $filepath, $line) {
		if ($severity == E_STRICT || !$message) {
			return false;
		}

		logger($message, $filepath, $line);
		return true;
	}

	@set_error_handler('_exception_handler');
}
