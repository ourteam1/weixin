<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 设置菜单
 */
class WechatSetMenu extends Action {

    function on_setMenu() {
        $u = isset($_REQUEST['u']) ? $_REQUEST['u'] : '';
        if ($u != 'wutong') {
            die('no access!');
        }

        // 获取菜单
        $res = $this->weixin->menu_get();

        if (isset($res['menu']) && count($res['menu']['button']) > 0) {
            die("菜单已经设置：" . var_export($res, true));
        }

        // 设置菜单
        $newmenu = array(
            "button" => array(               
                array('name' => '赚', "sub_button" => array(                        
                        array("type" => "scancode_waitmsg", "name" => "扫描码", "key" => "scancode"),
                        array("type" => "view", "name" => "输入码", "url" => "http://115.28.80.161/wutong/wzh/weixin/index.php/code/enter"),
                        array("type" => "view", "name" => "免费得金币", "url" => "http://115.28.80.161/wutong/wzh/weixin/index.php/code/list"),
                    )),
                array('name' => '花', "sub_button" => array(
                        array("type" => "view", "name" => "优惠卷", "url" => "http://115.28.80.161/wutong/wzh/weixin/index.php/favorable/index"),
                        array("type" => "view", "name" => "礼品专区", "url" => "http://115.28.80.161/wutong/wzh/weixin/index.php/goods/index/1"),
                        array("type" => "view", "name" => "爆款专区", "url" => "http://115.28.80.161/wutong/wzh/weixin/index.php/goods/index/2"),
                    )),
                 array('type' => 'view', 'name' => '会员中心', 'url' => 'http://115.28.80.161/wutong/wzh/weixin/index.php/user/'),
            )
        );

        $res = $this->weixin->menu_create($newmenu);
        die("设置菜单：" . ($res ? '成功！' : '失败！'));
    }

}
