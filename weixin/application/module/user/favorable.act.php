<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置
 * http://host/weixin/test.php/user/favorable
 */
class UserFavorable extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_favorable()
    {
        $date       = date('Y-m-d H:i:s');
        $sth        = $this->db->query("select * from ms_favorable left join ms_activity on ms_favorable.activity_code=ms_activity.activity_code where start_time<='$date' and end_time>='$date'");
        $favorables = $sth->fetchAll(PDO::FETCH_ASSOC);
        // $favorables = $this->db->where('user_id', $this->user_id)->where('start_time', '<=', $date)->where('end_time', '>=', $date)->result('favorable');
        //查询优惠券详情

        $this->view->assign('favorables', $favorables);
        $this->view->display('user/favorable.html');
    }

}
