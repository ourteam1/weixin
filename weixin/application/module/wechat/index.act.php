<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

define('SUBSCRIBE_INFOMATION', '亲爱的，感谢您关注玩赚花，我们为您准备了很多惊喜哦！亲，还等什么，赶紧开始吧！');

/**
 * 微信接口
 * http://host/weixin/index.php/weixin
 */
class WechatIndex extends Action
{

    /**
     * 程序入口
     */
    public function on_index()
    {
        $method = $this->weixin->get_rev_message();
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * 我要充值
     */
    public function _click_i_will_recharge()
    {
        $news_data = array(
            array(
                'title'       => '我要充值',
                'description' => '充值有惊喜，精选食材供您选哦！',
                'picurl'      => __PUBLIC__ . 'image/recharge.jpg',
                'url'         => site_url('user/recharge'),
            ),
        );
        $this->weixin->reply_news($news_data);
    }

    /**
     * 账户余额
     */
    public function _click_my_account()
    {
        $wx_openid = $this->weixin->get_rev_from();
        $user      = $this->db->where('wx_openid', $wx_openid)->row('user');
        $text_data = "您好，" . $user['nickname'] . "！您的账户余额：￥" . $user['amount'];
        $this->weixin->reply_text($text_data);
    }

    /**
     * 扫描
     */
    public function _scancode_waitmsg_scancode()
    {
        $codeArr = (array) $this->weixin->rev_message['ScanCodeInfo']->ScanResult;
        $codes   = explode(',', $codeArr[0]);
        $code    = $codes[1];
        if (strlen($code) != 13) {
            $this->weixin->reply_text('码的长度不正确');
        }

        //检查验证码
        $check_url                  = 'http://42.62.73.239:8080/CloudServer/wi.do?method=GetCodeState_WX&code=' . $code;
        $check_result               = file_get_contents($check_url);
        list($check_status, $score) = explode(',', $check_result);
        if ($check_status == '1') {
            $this->weixin->reply_text('金币券已被使用了');
            return false;
        }
        if ($check_status == '2') {
            $this->weixin->reply_text('金币券已经过期了');
            return false;
        }

        if ($check_status != '0') {
            $this->weixin->reply_text('金币券异常！');
            return false;
        }
        $score = empty($score) ? 0 : $score;

        // 微信用户信息
        $wx_openid = $this->weixin->get_rev_from();

        $user = $this->db->where('wx_openid', $wx_openid)->row('user');
        // $score = 10;
        $data = array(
            'score'       => $user['score'] + $score,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res    = $this->db->where('user_id', $user['user_id'])->update('user', $data);
        $resStr = $res === false ? '扫描获取金币失败' : "扫码获取金币成功";
        if ($res !== false) {
            // 记录账户变动记录
            $data = array(
                'user_id'     => $user['user_id'],
                'action'      => 'user.score.add',
                'action_name' => '增加金币' . $score . '元',
                'amount'      => $score,
                'create_time' => date('Y-m-d H:i:s'),
            );
            $res = $this->db->insert('user_account', $data);
        }

        $this->weixin->reply_text($resStr);
    }

    /**
     * 关注
     */
    public function _subscribe()
    {
        $userinfo = $this->weixin->get_userinfo();

        logger("关注者信息：", __FILE__, __LINE__);
        logger(var_export($userinfo, true), __FILE__, __LINE__);

        $user = $this->db->where('wx_openid', $userinfo['openid'])->row('user');
        if (!$user) {
            $data = array(
                'wx_openid'   => $userinfo['openid'],
                'nickname'    => $userinfo['nickname'],
                'sex'         => $userinfo['sex'],
                'city'        => $userinfo['city'],
                'province'    => $userinfo['province'],
                'country'     => $userinfo['country'],
                'headimgurl'  => $userinfo['headimgurl'],
                'create_time' => date('Y-m-d H:i:s'),
                'modify_time' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('user', $data);
        }

        //获取最新几条商品并且推送
        $goods_rows = $this->db->limit(0, 5)->where('status', 1)->order_by('create_time', 'desc')->result('goods');

        $news_data = array();
        foreach ($goods_rows as $key => $goods) {
            $news_data[] = array(
                'title'       => mb_substr($goods['goods_name'], 0, 15, 'utf-8'),
                'description' => '',
                'picurl'      => IMAGED . $goods['thumb'],
                'url'         => site_url('goods/index/' . $goods['category_id'] . '/' . $goods['goods_id']),
            );
        }
        $this->weixin->reply_news($news_data);
    }

}
