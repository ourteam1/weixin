<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class GoodsImage extends MS_Controller {

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 读取图片
	 */
	function index($filename) {
		$upload_path = FCPATH . 'upload/image/';
		if (!file_exists($upload_path . $filename)) {
			$filename = "default.jpg";
		}
		$img	 = @getimagesize($filename);
		$content = @file_get_contents($upload_path . $filename);
		header("Content-type: {$img['mime']}");
		echo $content;
	}

	/**
	 * 上传图片
	 */
	function upload() {
		$ufile		 = 'goods_image';
		$upload_path = FCPATH . 'upload/image/';

		// 上传图片
		$config	 = array(
			'upload_path'	 => $upload_path,
			'allowed_types'	 => 'jpg|jpeg|png|gif',
			'max_size'		 => 1024,
		);
		$this->load->library('upload');
		$this->upload->initialize($config);
		$res	 = $this->upload->do_upload($ufile);
		if (!$res) {
			die_json(array('error' => '上传图片失败！' . json_encode($this->upload->error_msg, JSON_UNESCAPED_UNICODE)));
		}
		$image_data		 = $this->upload->data();
		$image_full_path = $image_data['full_path'];

		// 缩略图
		$config	 = array(
			'image_library'	 => 'gd2',
			'source_image'	 => $image_full_path,
			'create_thumb'	 => true,
			'maintain_ratio' => false,
			'width'			 => 80,
			'height'		 => 80,
		);
		$this->load->library('image_lib', $config);
		$res	 = $this->image_lib->resize();
		if (!$res) {
			@unlink($image_full_path);
			die_json(array('error' => '生成缩略图失败！'));
		}
		$thumb_full_path = $this->image_lib->full_dst_path;

		// 上传到远程服务器
		$res = upload_image(UPLOAD_IMAGE, $image_full_path);
		$res = json_decode($res, true);
		if ($res === false) {
			@unlink($image_full_path);
			@unlink($thumb_full_path);
			die_json(array('error' => '上传图片失败！'));
		}
		$image_file = $res['filename'];

		// 上传到远程服务器 - 缩略图
		$res = upload_image(UPLOAD_IMAGE, $thumb_full_path);
		$res = json_decode($res, true);
		if ($res === false) {
			@unlink($image_full_path);
			@unlink($thumb_full_path);
			die_json(array('error' => '生成缩略图失败！'));
		}
		$thumb_file = $res['filename'];

		// 删除临时文件
		@unlink($image_full_path);
		@unlink($thumb_full_path);

		// 返回数据
		$image_thumb_url = IMAGED . $thumb_file;
		$data			 = array(
			'message'	 => '上传成功！',
			'image_url'	 => $image_thumb_url,
			'image'		 => $image_file,
			'thumb'		 => $thumb_file
		);
		die_json($data);
	}

	/**
	 * 上传远程图片
	 */
	function remote_upload() {
		$ufile		 = 'imgFile';
		$upload_path = FCPATH . 'upload/image/';

		// 上传图片
		$config	 = array(
			'upload_path'	 => $upload_path,
			'allowed_types'	 => 'jpg|jpeg|png|gif',
			'max_size'		 => 1024,
		);
		$this->load->library('upload');
		$this->upload->initialize($config);
		$res	 = $this->upload->do_upload($ufile);
		if (!$res) {
			die_json(array('error' => 1, 'message' => '上传图片失败！' . json_encode($this->upload->error_msg, JSON_UNESCAPED_UNICODE)));
		}
		$image_data		 = $this->upload->data();
		$image_full_path = $image_data['full_path'];

		// 上传到远程服务器
		$res = upload_image(UPLOAD_IMAGE, $image_full_path);
		$res = json_decode($res, true);
		if ($res === false) {
			@unlink($image_full_path);
			die_json(array('error' => 2, 'message' => '上传图片失败！'));
		}
		$image_file = $res['filename'];

		// 返回数据
		$data = array(
			'error'		 => 0,
			'message'	 => '上传成功！',
			'filename'	 => $image_file,
			'url'		 => IMAGED . $image_file,
		);
		die_json($data);
	}

	/**
	 * 裁剪图片
	 */
	function crop() {
		$image_file			 = $_REQUEST['image'];
		$info				 = pathinfo($image_file);
		$image_crop_file	 = $info['filename'] . '_crop.' . $info['extension'];
		$image_thumb_file	 = $info['filename'] . '_thumb.' . $info['extension'];

		$crop_offset = $_REQUEST['crop_offset'];
		if (!is_array($crop_offset)) {
			$crop_offset = explode(',', $crop_offset);
		}

		$upload_path = FCPATH . 'upload/image/';
		list($width, $height, $type, $attr) = getimagesize($upload_path . $image_file);

		// 判断
		if (!file_exists($upload_path . $image_file)) {
			die_json(array('error' => '图片不存在！'));
		}

		$x		 = $crop_offset[0]; // 裁剪的x坐标
		$y		 = $crop_offset[1]; // 裁剪的y坐标
		$w		 = $crop_offset[2]; // 裁剪的相对宽度
		$h		 = $crop_offset[3]; // 裁剪的相对高度
		$bw		 = $crop_offset[4]; // 原图显示宽度
		$bh		 = $crop_offset[5]; // 原图显示高度
		$pw		 = $width / $bw; // 宽度比例
		$ph		 = $height / $bh; // 高度比例
		// 裁剪图
		$config	 = array(
			'image_library'	 => 'gd2',
			'source_image'	 => $upload_path . $image_file,
			'new_image'		 => $upload_path . $image_crop_file,
			'maintain_ratio' => false,
			'width'			 => $w * $pw,
			'height'		 => $h * $ph,
			'x_axis'		 => $x * $pw,
			'y_axis'		 => $y * $ph,
		);
		$this->load->library('image_lib', $config);
		$res	 = $this->image_lib->crop();
		if (!$res) {
			die_json(array('error' => '图片裁剪失败！'));
		}

		// 缩略图
		$config	 = array(
			'image_library'	 => 'gd2',
			'source_image'	 => $upload_path . $image_crop_file,
			'new_image'		 => $upload_path . $image_thumb_file,
			'maintain_ratio' => false,
			'width'			 => 120,
			'height'		 => 120,
		);
		$this->image_lib->initialize($config);
		$res	 = $this->image_lib->resize();
		if (!$res) {
			die_json(array('error' => '生成缩略图失败！'));
		}

		die_json(array('message' => '上传成功！', 'image' => $image_crop_file, 'thumb' => $image_thumb_file));
	}

}
