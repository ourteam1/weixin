<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 
 * http://host/weixin/test.php/code/game
 */
class CommonBarcode extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_barcode($number = '') {
        if (empty($number)) {
            die_json(array('error_code'=>10041, '条形码不能为空'));
        }
        include_once LIBS_DIR . 'barcode/class/BCGFontFile.php';
        include_once LIBS_DIR . 'barcode/class/BCGColor.php';
        include_once LIBS_DIR . 'barcode/class/BCGDrawing.php';
        include_once LIBS_DIR . 'barcode/class/BCGcode39.barcode.php';

        // 加载字体大小
        $font = new BCGFontFile(LIBS_DIR . 'barcode/font/Arial.ttf', 18);

        //颜色条形码
        $color_black = new BCGColor(0, 0, 0);
        $color_white = new BCGColor(255, 255, 255);

        $drawException = null;
        try {
            $code = new BCGcode39();
            $code->setScale(2);
            $code->setThickness(30); // 条形码的厚度
            $code->setForegroundColor($color_black); // 条形码颜色
            $code->setBackgroundColor($color_white); // 空白间隙颜色
            $code->setFont($font); // 
            $code->parse($number); // 条形码需要的数据内容
        } catch (Exception $exception) {
            $drawException = $exception;
        }

        //根据以上条件绘制条形码
        $drawing = new BCGDrawing('', $color_white);
        if ($drawException) {
            $drawing->drawException($drawException);
        } else {
            $drawing->setBarcode($code);
            $drawing->draw();
        }

        // 生成PNG格式的图片
        header('Content-Type: image/png');

        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
    }

}
