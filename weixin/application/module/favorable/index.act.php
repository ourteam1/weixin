<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

/**
 * 优惠卷列表
 * http://host/weixin/test.php/favorable
 */
class FavorableIndex extends Action {

    function __construct() {
        parent::__construct();
        $this->wxauth();
    }

    function on_index() {

        $arr = array(
            array(
                'code' => '3201506117212',
                'name' => 'M折扣-大0617伊利奶粉送80元',
                'score'=> 10,
                'start_time' => '2015-03-01',
                'end_time' => '2015-09-30',
            ),
            array(
                'code' => '3201501196874',
                'name' => 'R折扣-营0120物美会员满200送5',
                'score'=> 15,
                'start_time' => '2015-03-01',
                'end_time' => '2015-08-30',
            ),
            array(
                'code' => '3201506117213',
                'name' => 'R折扣-营0120物美会员购买燕京200送5',
                'score'=> 20,
                'start_time' => '2015-04-01',
                'end_time' => '2015-11-31',
            ),
        );
        
        // 领取的优惠卷
        $activityNums = array();
        $userFavorable = $this->db->where('user_id', $this->user_id)->result('favorable');
        foreach($userFavorable as $favorable) {
            $activityNums[] = $favorable['activity_num'];
        }

        $this->view->assign('activiyNums', $activityNums);
        $this->view->assign('favorables', $arr);

        $this->view->display('favorable/index.html');
    }

}
