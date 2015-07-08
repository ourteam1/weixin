<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

/**
 * 设置
 * http://host/weixin/test.php/user/favorable_detail
 */
class UserFavorable_detail extends Action
{

    public function __construct()
    {
        parent::__construct();
        $this->wxauth();
    }

    public function on_favorable_detail()
    {
        $favorable_id = get_post('id');
        if (!$favorable_id) {
            $this->error_message('缺少参数');
        }

        $date = date('Y-m-d H:i:s');
        // $favorable = $this->db->where('user_id', $this->user_id)->where('favorable_id', $favorable_id)->where('start_time', '<=', $date)->where('end_time', '>=', $date)->row('favorable');
        $sth                     = $this->db->query("select * from ms_favorable left join ms_activity on ms_favorable.activity_code=ms_activity.activity_code where start_time<='$date' and end_time>='$date' and favorable_id='$favorable_id'");
        $favorable               = $sth->fetch(PDO::FETCH_ASSOC);
        $favorable['tiaoxingma'] = file_get_contents('http://42.62.73.239:8080/CloudServer/wi.do?method=GetDiscountCode_DX&hdbh=' . $favorable['favorable_code']);
        foreach ($favorable as $key => $value) {
            $this->view->assign($key, $value);
        }
        $this->view->display('user/favorable_detail.html');
    }

}
