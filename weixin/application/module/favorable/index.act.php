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
        $activityUrl = "http://42.62.73.239:8080/CloudServer/wi.do?method=GetDiscount_WX";
        $activityXmlObj = file_get_contents($activityUrl);
        $activityXmlObj = iconv('gbk', 'utf-8', $activityXmlObj);
        $res = @simplexml_load_string($activityXmlObj,NULL,LIBXML_NOCDATA);
        $activityArr = json_decode(json_encode($res),true);
        
        $arr = array();
        foreach ($activityArr['discount'] as $activity) {
           
            $arr[] = array(
                'code'       => $activity['hdbh'],
                'name'       => $activity['hdnr'],
                'score'      => $activity['gold'],
                'start_time' => $activity['kssj'],
                'end_time'   => $activity['jssj'],
            );
        }       
        
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
