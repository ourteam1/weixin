<?php

if (!defined('IN_MSAPP')) exit('Access Deny!');

define('SUBSCRIBE_INFOMATION', '亲爱的，感谢您关注玩赚花，我们为您准备了很多惊喜哦！亲，还等什么，赶紧开始吧！');

/**
 * 微信接口
 * http://host/weixin/index.php/weixin
 */
class WechatIndex extends Action {

    /**
     * 程序入口
     */
    function on_index() {
        $method = $this->weixin->get_rev_message();
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * 我要充值
     */
    function _click_i_will_recharge() {
        $news_data = array(
            array(
                'title' => '我要充值',
                'description' => '充值有惊喜，精选食材供您选哦！',
                'picurl' => __PUBLIC__ . 'image/recharge.jpg',
                'url' => site_url('user/recharge'),
            ),
        );
        $this->weixin->reply_news($news_data);
    }

    /**
     * 账户余额
     */
    function _click_my_account() {
        $wx_openid = $this->weixin->get_rev_from();
        $user = $this->db->where('wx_openid', $wx_openid)->row('user');
        $text_data = "您好，" . $user['nickname'] . "！您的账户余额：￥" . $user['amount'];
        $this->weixin->reply_text($text_data);
    }

    /**
     * 扫描
     */
    function _scancode_waitmsg_scancode() {
        $codeArr = (array) $this->weixin->rev_message['ScanCodeInfo']->ScanResult;
        $codes = explode(',', $codeArr[0]);
        $code = $codes[1];
        if (strlen($code) != 13) {
            $this->weixin->reply_text('码的长度不正确');
        }

        if ($code != '5031505301298') {
            $this->weixin->reply_text('码的不正确');
        }
        
        // 微信用户信息
        $wx_openid = $this->weixin->get_rev_from();

        $user = $this->db->where('wx_openid', $wx_openid)->row('user');
        $score = 10;
        $data = array(
            'score' => $user['score'] + $score,
            'modify_time' => date('Y-m-d H:i:s'),
        );
        $res = $this->db->where('user_id', $user['user_id'])->update('user', $data);          
        $resStr = $res === false ? '扫描获取金币失败' : "扫码获取金币成功";
        if ($res !== false ) {
            // 记录账户变动记录
            $data = array(
                'user_id' => $user['user_id'],
                'action' => 'user.score.add',
                'action_name' => '增加金币' . $score . '元',
                'amount' => $score,
                'create_time' => date('Y-m-d H:i:s'),
            );
            $res = $this->db->insert('user_account', $data);
        }
        
        $this->weixin->reply_text($resStr);
    }

    /**
     * 关注
     */
    function _subscribe() {
        $userinfo = $this->weixin->get_userinfo();

        logger("关注者信息：", __FILE__, __LINE__);
        logger(var_export($userinfo, true), __FILE__, __LINE__);

        $user = $this->db->where('wx_openid', $userinfo['openid'])->row('user');
        if (!$user) {
            $data = array(
                'wx_openid' => $userinfo['openid'],
                'nickname' => $userinfo['nickname'],
                'sex' => $userinfo['sex'],
                'city' => $userinfo['city'],
                'province' => $userinfo['province'],
                'country' => $userinfo['country'],
                'headimgurl' => $userinfo['headimgurl'],
                'create_time' => date('Y-m-d H:i:s'),
                'modify_time' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('user', $data);
        }

        $news_data = array(
            array(
                'title' => '亲爱的，感谢您关注玩赚花，我们为您准备了很多惊喜哦！',
                'description' => SUBSCRIBE_INFOMATION,
                'picurl' => __PUBLIC__ . 'image/subscribe.jpg',
                'url' => site_url('goods/index'),
            ),
        );
        $this->weixin->reply_news($news_data);
    }

}
