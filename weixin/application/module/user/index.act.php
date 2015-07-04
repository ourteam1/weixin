<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 会员中心
 * http://host/weixin/test.php/user/index
 */
class UserIndex extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_index() {
        $this->view->display('user/index.html');
    }

}
