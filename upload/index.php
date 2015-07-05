<?php
$uploadFolder="data/";
// 上传文件
if (!empty($_FILES["upfile"]["tmp_name"])) {
	$uploadType = array(  
		'image/jpg',  
		'image/jpeg',  
		'image/png',  
		'image/pjpeg',  
		'image/gif',  
		'image/bmp',  
		'image/x-png'  
	); 

	$file = $_FILES["upfile"];  

	//检查文件类型  
	if(!in_array($file["type"], $uploadType)) {  	
		// exit("类型不对" . $file["type"]);  
	}
	
	$fileInfo = file_get_contents($_FILES["upfile"]["tmp_name"]);
	$pinfo    = pathinfo($file["name"]);  
	$fileName = md5($fileInfo) . "." . $pinfo['extension'];
	if(!move_uploaded_file ($file["tmp_name"], $uploadFolder . $fileName)) {         
        exit("失败");  
    }
	$arr = array(
		'filename' => $fileName
	);
	die(json_encode($arr));
} 