<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Image extends MS_Controller {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 
	 * @return type获取图片列表
	 */
	public function index() {

		$img_url = isset($_REQUEST['src']) ? trim($_REQUEST['src']) : '';

		if (!$img_url) {
			die('');
		}

		//检查图片
		$img_url = $this->check_img($img_url);
		$img	 = @getimagesize($img_url);
		if ($img === false) {
			die('');
		}

		$content = @file_get_contents($img_url);
		header("Content-type: {$img['mime']}");
		echo $content;
	}

	/**
	 * 检查图片的存在路径
	 * @param type $src
	 */
	function check_img($_img_url = '') {
		$img_url = '';
		// 判断本地目录
		if (!file_exists($img_url)) {
			$img_url = FCPATH . '/' . $_img_url;
		}

		if (!file_exists($img_url)) {
			$img_url = FCPATH . 'upload/' . $_img_url;
		}
		if (!file_exists($img_url)) {
			$img_url = FCPATH . 'upload/image/' . $_img_url;
		}
		if (!file_exists($img_url)) {
			$img_url = dirname(FCPATH) . '/www/upload/image/' . $_img_url;
		}
		return $img_url;
	}

	/**
	 * 上传图片
	 * 上传图片到本地：index.php/image/upload
	 * 上传图片到图片服务器：index.php/image/upload/remote
	 */
	function upload() {
		$ufile			 = isset($_REQUEST['file_name']) ? trim($_REQUEST['file_name']) : 'ufile'; // 获取上传表单名字
		$file_types_str	 = isset($_REQUEST['file_types']) ? trim($_REQUEST['file_types']) : ''; // 获取图片类型
		$file_width		 = isset($_REQUEST['width']) ? trim($_REQUEST['width']) : ''; //get_post('width'); // 图片宽
		$file_height	 = isset($_REQUEST['height']) ? trim($_REQUEST['height']) : ''; //get_post('height'); // 图片高
		$file_size		 = isset($_REQUEST['size']) ? trim($_REQUEST['size']) : ''; //get_post('size'); // 图片大小
		// 上传图片的信息
		$filename		 = $_FILES[$ufile]['name'];
		$tmpfile		 = $_FILES[$ufile]['tmp_name'];
		$filesize		 = $_FILES[$ufile]['size'];
		$fileinfo		 = pathinfo($filename);
		$file_types		 = explode(',', $file_types_str);
		!$file_types && $file_types		 = array('jpg', 'jpeg', 'png', 'gif');

		// 判断文件格式
		if (isset($fileinfo['extension']) && !in_array(strtolower($fileinfo['extension']), $file_types)) {
			return array('error' => "文件格式不正确！");
		}
		// 判断文件大小
		if ($file_size != '' && $file_size * 1024 < $filesize) {
			return array('error' => "文件大小不符合规范");
		}

		if (!trim($tmpfile)) {
			return array("error" => "上传文件为不能空！");
		}

		$imagesize = getimagesize($tmpfile);
		// 判断文件高宽
		if (($file_width != '' && $file_width != $imagesize[0]) || ($file_height != '' && $file_height != $imagesize[1])) {
			return array("error" => "文件尺寸不符合规范");
		}


		$file = FCPATH . 'upload/image/' . time() . rand(999, 1000) . "." . $fileinfo['extension'];
		@move_uploaded_file($tmpfile, $file);

		$data = array('message' => '上传成功！');

		// 上传到本地
		$data['filename']	 = basename($file);
		$data['file']		 = $data['file_url']	 = site_url('image/index?src=' . $data['filename']);

		die_json($data);
	}

}
