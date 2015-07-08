<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 活动详情
 * http://host/weixin/test.php/favorable
 */
class FavorableDetail extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_detail($activity_code) {                                               
        $result = $this->db->where('activity_code', $activity_code)->row('activity');
        $this->view->assign('favorable', $result);
        $this->view->display('favorable/detail.html');
    }

}
