<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Http {

	var $ch;
	var $timeout = 60;

	function __construct() {
		$this->ch = curl_init();
	}

	function __destruct() {
		if ($this->ch) {
			@curl_close($this->ch);
		}
	}

	// post request
	function post($url, $data = array(), $return_header = 0, $header = '') {
		if ($header) {
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header); // 发送头
		}
		curl_setopt($this->ch, CURLOPT_URL, trim($url));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);  // 获取的信息以文件流的形式返回
		curl_setopt($this->ch, CURLOPT_POST, true);  // 发送一个常规的Post请求
		if ($data) {
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);  // Post提交的数据包
		}
		if (strpos($url, 'https') !== false) {
			curl_setopt($this->ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		}
		curl_setopt($this->ch, CURLOPT_HEADER, $return_header);  // 显示返回的Header区域内容
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout); // 设置超时限制防止死循环
		$result = curl_exec($this->ch);
		if (curl_errno($this->ch)) {
			$result = curl_error($this->ch); // 捕抓异常
		}
		return $result;
	}

	// get request
	function get($url, $return_header = 0) {
		curl_setopt($this->ch, CURLOPT_URL, trim($url));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);  // 获取的信息以文件流的形式返回
		curl_setopt($this->ch, CURLOPT_HEADER, $return_header); // 显示返回的Header区域内容
		if (strpos($url, 'https') !== false) {
			curl_setopt($this->ch, CURLOPT_SSLVERSION, 3);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		}
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout); // 设置超时限制防止死循环
		$result = curl_exec($this->ch);
		if (curl_errno($this->ch)) {
			$result = curl_error($this->ch); // 捕抓异常
		}
		return $result;
	}

}
