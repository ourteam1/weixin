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
        $dataList = array(
            array( 'code' => 1,  'name' => '滴滴打车', 'score' => 100),
            array( 'code' => 2,  'name' => '51用车', 'score' => 150),
            array( 'code' => 3,  'name' => '家政无忧', 'score' => 200),
            array( 'code' => 4,  'name' => '泰迪洗车', 'score' => 100),
            array( 'code' => 5,  'name' => '百度打车', 'score' => 120),
        );
        
        $freeCodes = array();
        $freeGetList = $this->db->where('user_id', $this->user_id)->result('free_get_score');
        foreach($freeGetList as $freeCode) {
            $freeCodes[] = $freeCode['code'];
        }
        
        $this->view->assign('freeCodes', $freeCodes);
        $this->view->assign('dataList', $dataList);
        $this->view->display('code/list.html');
    }
}
