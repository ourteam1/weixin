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
            array('name'=>'摇一摇得金币', 'fold'=>'yao', 'icon'=>'app/yao/icon.png'),
            array('name'=>'开心刮刮乐', 'fold'=>'money', 'icon'=>'app/money/icon.png'),
            array('name'=>'抽奖赢金币', 'fold'=>'choujiang', 'icon'=>'app/choujiang/icon.png'),
        );
        $this->view->assign('games', $games);
        $this->view->display('code/gamelist.html');
    }

}
