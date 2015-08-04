<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 优惠卷列表
 * http://host/weixin/test.php/favorable
 */
class FavorableIndex extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_index()
    {
        $activityUrl    = "http://42.62.73.239:8080/CloudServer/wi.do?method=GetDiscount_WX";
        $activityXmlObj = file_get_contents($activityUrl);
        // logger('activityXmlObj'.$activityXmlObj);
        $activityXmlObj = iconv('gbk', 'utf-8', $activityXmlObj);
        $res            = @simplexml_load_string($activityXmlObj, null, LIBXML_NOCDATA);
        $activityArr    = json_decode(json_encode($res), true);

        logger('activityArr' . var_export($activityArr, true));

        $arr           = array();
        $activityCodes = array();
        $discount      = $activityArr['discount'];
        if (isset($discount['hdbh'])) {
            $discount = array($discount);
        }
        foreach ($discount as $activity) {
            $activityCodes[]        = $activity['hdbh'];
            $arr[$activity['hdbh']] = array(
                'activity_code'  => $activity['hdbh'],
                'activity_name'  => $activity['hdnr'],
                'activity_img'   => $activity['tp'],
                'activity_score' => $activity['gold'],
                'start_time'     => $activity['kssj'],
                'end_time'       => $activity['jssj'],
                'detail'         => $activity['detail'],
            );
        }
        //logger('arr='.var_export($arr,true));
        // 获取优惠卷列表
        $activityOldArr = array();
        $activitys      = $this->db->select('activity_code,activity_name,activity_score,start_time,end_time,detail')->where('activity_code', 'in', $activityCodes)->result('activity');
        //logger('activitys='.var_export($activitys,true));
        foreach ($activitys as $activity) {
            $activityOldArr[$activity['activity_code']] = $activity;
        }

        // 优惠活动处理
        $fields = array('activity_code', 'activity_name', 'activity_score', 'start_time', 'end_time', 'detail');
        foreach ($arr as $activityCode => $activity) {
            if (!isset($activityOldArr[$activityCode])) {
                $this->_add_activity($activity);
                continue;
            }
            $isUpdate    = false;
            $oldActivity = $activityOldArr[$activityCode];
            foreach ($fields as $field) {

                if ($activity[$field] != $oldActivity[$field]) {
                    $isUpdate = true;
                    break;
                }
            }

            if ($isUpdate) {
                unset($activity['activity_code']);
                $this->_update_activity($activity_code, $activity);
            }
        }

        // 领取的优惠卷
        //$sql = "select f.favorable_id,a.activity_code,f.favorable_code,a.activity_name,a.activity_img,a.activity_score,a.start_time,a.end_time from ms_favorable f right join ms_activity a on f.activity_code=a.activity_code where (f.user_id=" . $this->user_id . " or f.user_id is null) and a.start_time<='" . date('Y-m-d H:i:s') . "' and a.end_time >= '" . date('Y-m-d H:i:s') . "'";
        $sql = "select f.favorable_id,a.activity_code,f.favorable_code,a.activity_name,a.activity_img,a.activity_score,a.start_time,a.end_time from ms_favorable f right join ms_activity a on f.activity_code=a.activity_code where a.start_time<='" . date('Y-m-d H:i:s') . "' and a.end_time >= '" . date('Y-m-d H:i:s') . "'";
        //$sql = "select f.favorable_id,a.activity_code,f.favorable_code,a.activity_name,a.activity_img,a.activity_score,a.start_time,a.end_time from ms_favorable f right join ms_activity a on f.activity_code=a.activity_code where a.start_time>='" . date('Y-m-d H:i:s') . "'";
        logger('sql' . $sql);
        $result = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        logger('result' . var_export($result, true));
        $this->view->assign('favorables', $result);
        $this->view->display('favorable/index.html');
    }

    public function _add_activity($data)
    {
        $data['create_time'] = date("Y-m-d H:i:s");
        $res                 = $this->db->insert('activity', $data);
        if (false === $res) {
            logger("活动添加失败：" . var_export($data, true));
        }
    }

    public function _update_activity($activity_code, $data)
    {
        $res = $this->db->where('activity_code', $activity_code)->update('activity', $data);
        if (false === $res) {
            logger("活动添加失败：" . var_export($data, true));
        }
    }

}
