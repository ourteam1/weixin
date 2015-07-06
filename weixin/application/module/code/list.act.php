<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 
 * http://host/weixin/test.php/code/list
 */
class CodeList extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_list() {
        // 获取关注列表
        $focusList = $this->db->where('status', 1)->order_by('sort', 'asc')->result('focus');
        foreach ($focusList as $k => $i) {
            $i['icon'] = !empty($i['icon']) ? IMAGED . $i['icon'] : '';
            $focusList[$k] = $i;
        }

        $freeCodes = array();
        $freeGetList = $this->db->where('user_id', $this->user_id)->result('free_get_score');
        foreach($freeGetList as $freeCode) {
            $freeCodes[] = $freeCode['code'];
        }
        
        $this->view->assign('freeCodes', $freeCodes);
        $this->view->assign('focusList', $focusList);
        $this->view->display('code/list.html');
    }
}
