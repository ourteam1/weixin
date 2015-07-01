<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 显示商品图片
 */
class GoodsImage extends Action {

	function __construct() {
		parent::__construct();

		$this->wxauth();
	}

	function on_image($filename) {
		if (!file_exists(IMAGE_DOWNLOAD . $filename)) {
			$filename = "default.jpg";
		}
		$img	 = @getimagesize(IMAGE_DOWNLOAD . $filename);
		$content = @file_get_contents(IMAGE_DOWNLOAD . $filename);
		header("Content-type: {$img['mime']}");
		echo $content;
	}

}
