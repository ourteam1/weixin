<?php
if (!defined('IN_MSAPP'))
    exit('Access Deny!');
/**
 * 
 * http://host/weixin/test.php/code/gamelist
 */
class CodeGamelist extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_gamelist() {
        $games = array(
            array('name'=>'摇一摇得金币', 'fold'=>'yao'),
            array('name'=>'快来一起捡钱玩', 'fold'=>'money'),
			array('name'=>'抽奖赢金币', 'fold'=>'choujiang),
        );
        $this->view->assign('games', $games);
        $this->view->display('code/gamelist.html');
    }

}
