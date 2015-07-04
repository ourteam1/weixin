<?php

/**
 * 微信基础类
 */
class WXBase {

    const WX_API_URL = 'https://api.weixin.qq.com';
    const WX_OPEN_URL = 'https://open.weixin.qq.com';

    var $token = null;
    var $appid = null;
    var $appsecret = null;
    var $log_callback = null;
    var $debug = true;

    /**
     * 构造函数
     */
    function __construct($options) {
        $this->appid = isset($options['appid']) ? $options['appid'] : '';
        $this->appsecret = isset($options['appsecret']) ? $options['appsecret'] : '';
        $this->token = isset($options['token']) ? $options['token'] : '';
        $this->log_callback = isset($options['log_callback']) ? $options['log_callback'] : '';
        $this->debug = isset($options['debug']) ? $options['debug'] : false;
    }

    /**
     * 发送请求
     */
    function request($url, $data = null, $header = null) {
        $ch = curl_init();
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_URL, trim($url));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (strpos($url, 'https') !== false) {
            curl_setopt($ch, CURLOPT_SSLVERSION, 4);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 65);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = curl_error($ch);
        }
        @curl_close($ch);
        $_result = json_decode($result, true);
        return $_result ? $_result : $result;
    }

    /**
     * 回复信息
     */
    function reply($message) {
        @ob_clean();
        die($message);
    }

    /**
     * 记录日志
     */
    function log($message, $filename = '', $linenum = '') {
        if (!$this->debug) {
            return false;
        }
        if (trim($this->log_callback) != '') {
            return call_user_func($this->log_callback, $message, $filename, $linenum);
        }
        $message = date('Y-m-d H:i:s') . " $message ";
        if ($filename) {
            $message .= " --> {$filename} {$linenum}";
        }
        $log_file = 'wx.' . date('Ymd') . '.log';
        file_put_contents($log_file, "{$message} \r\n", FILE_APPEND);
    }

}

/**
 * 微信授权OAuth2认证
 */
class WXOAuth extends WXBase {

    const WX_OAUTH2_URI = "/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=%s#wechat_redirect";
    const WX_SNS_ACCESS_TOKE_URI = "/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";
    const WX_SNS_REFRESH_TOKEN_URI = "/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s";
    const WX_SNS_USERINFO_URI = "/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN";

    /**
     * 构造函数
     */
    function __construct($options) {
        parent::__construct($options);

        if (!$this->chk_session_access_token()) {
            $this->log("begin auth ...", __FILE__, __LINE__);
            $this->auth();
            $this->log("end auth ...", __FILE__, __LINE__);
        }
    }

    /**
     * 微信oauth2认证
     */
    function auth() {
        // 2. 获取code
        if (isset($_GET['state'])) {
            if (!isset($_SESSION['WX_OAUTH2_STATE']) || $_GET['state'] != $_SESSION['WX_OAUTH2_STATE']) {
                die('no access!');
            }
            if (!isset($_GET['code'])) {
                die('用户授权失败，请重新授权!');
            }
            unset($_SESSION['WX_OAUTH2_STATE']);
            // 3. 获取access token
            $sns_access_token_url = self::WX_API_URL . sprintf(self::WX_SNS_ACCESS_TOKE_URI, $this->appid, $this->appsecret, $_GET['code']);
            $this->log("sns_access_token_url: $sns_access_token_url", __FILE__, __LINE__);
            $token_info = $this->request($sns_access_token_url);
            $this->log("result: " . var_export($token_info, true), __FILE__, __LINE__);
            if (!isset($token_info['access_token'])) {
                die('用户授权失败，获取ACCESS TOKEN失败!');
            }
            $token_info['expires_timeout'] = time() + $token_info['expires_in'] - 100;
            $_SESSION['WX_SNS_ACCESS_TOKEN'] = $token_info;
            return true;
        }

        // 1. 访问授权页面
        $_SESSION['WX_OAUTH2_STATE'] = mt_rand();
        $redirect = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $oauth_url = self::WX_OPEN_URL . sprintf(self::WX_OAUTH2_URI, $this->appid, $redirect, $_SESSION['WX_OAUTH2_STATE']);
        $this->log("oauth_url: $oauth_url", __FILE__, __LINE__);
        header('Location: ' . $oauth_url);
    }

    /**
     * 检测缓存数据
     */
    function chk_session_access_token() {
        if (!isset($_SESSION['WX_SNS_ACCESS_TOKEN'])) {
            return false;
        }
        $token_info = $_SESSION['WX_SNS_ACCESS_TOKEN'];
        if (time() < $token_info['expires_timeout']) {
            return true;
        }
        return $this->refresh_sns_access_token($token_info['refresh_token']);
    }

    /**
     * 刷新 access token
     */
    function refresh_sns_access_token($refresh_token) {
        $refresh_access_token_url = self::WX_API_URL . sprintf(self::WX_SNS_REFRESH_TOKEN_URI, $this->appid, $refresh_token);
        $this->log("refresh_access_token_url: $refresh_access_token_url", __FILE__, __LINE__);
        $token_info = $this->request($refresh_access_token_url);
        $this->log("result: " . var_export($token_info, true), __FILE__, __LINE__);
        if (!isset($token_info['access_token'])) {
            return false;
        }
        $token_info['expires_timeout'] = time() + $token_info['expires_in'] - 100;
        $_SESSION['WX_SNS_ACCESS_TOKEN'] = $token_info;

        return true;
    }

    /**
     * 获取用户openid
     */
    function get_openid() {
        if (!isset($_SESSION['WX_SNS_ACCESS_TOKEN']) || !$_SESSION['WX_SNS_ACCESS_TOKEN']) {
            $this->log("session WX_SNS_ACCESS_TOKEN or WX_SNS_ACCESS_TOKEN empty!!!", __FILE__, __LINE__);
            return null;
        }
        $token_info = $_SESSION['WX_SNS_ACCESS_TOKEN'];
        return $token_info['openid'];
    }

    /**
     * 获取用户信息
     */
    function get_userinfo() {
        $this->log($_SESSION['WX_SNS_ACCESS_TOKEN'], __FILE__, __LINE__);
        $this->log($_SESSION['WX_SNS_ACCESS_TOKEN'], __FILE__, __LINE__);
        if (!isset($_SESSION['WX_SNS_ACCESS_TOKEN']) || !$_SESSION['WX_SNS_ACCESS_TOKEN']) {
            $this->log("session WX_SNS_ACCESS_TOKEN or WX_SNS_ACCESS_TOKEN empty!!!", __FILE__, __LINE__);
            return null;
        }
        $token_info = $_SESSION['WX_SNS_ACCESS_TOKEN'];
        $sns_userinfo_url = self::WX_API_URL . sprintf(self::WX_SNS_USERINFO_URI, $token_info['access_token'], $token_info['openid']);
        $this->log("sns_userinfo_url: $sns_userinfo_url", __FILE__, __LINE__);
        $userinfo = $this->request($sns_userinfo_url);
        $this->log("result: " . var_export($userinfo, true), __FILE__, __LINE__);
        if (!isset($userinfo['openid'])) {
            return null;
        }
        return $userinfo;
    }

}

/**
 * 消息管理
 */
class WeiXin extends WXBase {

    const WX_ACCESS_TOKEN_URI = '/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
    const WX_USERINFO_URI = '/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN';
    const WX_CUSTOM_URI = '/cgi-bin/message/custom/send?access_token=%s';
    const WX_MENU_CREATE_URI = '/cgi-bin/menu/create?access_token=%s';
    const WX_MENU_GET_URI = '/cgi-bin/menu/get?access_token=%s';
    const WX_MENU_DEL_URI = '/cgi-bin/menu/delete?access_token=%s';

    var $rev_message = null;

    /**
     * 构造函数
     */
    function __construct($options) {
        parent::__construct($options);
    }

    /**
     * 获取access_token
     * {"access_token":"ACCESS_TOKEN","expires_in":7200}
     */
    function get_access_token($flag = false) {
        // 从存储中查询数据
        if (!$flag && class_exists('Memcached')) {
            $m = new Memcached(MEMCACHE_ID);
            $m->addServer(MEMCACHE_HOST, MEMCACHE_PORT);
            $access_token = $m->get('wzh.weixin.token.access_token');
            $this->log("session access token: " . $access_token, __FILE__, __LINE__);
            if (trim($access_token) != '') {
                return $access_token;
            }
            $this->log("session access token timeout!!!", __FILE__, __LINE__);
        }

        // 调用微信接口，获取access token
        $access_token_url = self::WX_API_URL . sprintf(self::WX_ACCESS_TOKEN_URI, $this->appid, $this->appsecret);
        $this->log($access_token_url, __FILE__, __LINE__);
        $token_info = $this->request($access_token_url);
        $this->log("result: " . var_export($token_info, true), __FILE__, __LINE__);
        if (!isset($token_info['access_token'])) {
            $this->log("get access token failed!!!", __FILE__, __LINE__);
            return null;
        }

        // 存储
        if (class_exists('Memcached')) {
            $m = new Memcached(MEMCACHE_ID);
            $m->addServer(MEMCACHE_HOST, MEMCACHE_PORT);

            $expires_timeout = time() + $token_info['expires_in'] - 100;
            $m->set('wzh.weixin.token.access_token', $token_info['access_token'], $expires_timeout);
        }
        return $token_info['access_token'];
    }

    /**
     * 获取用户信息
     */
    function get_userinfo() {
        $userinfo_url = self::WX_API_URL . sprintf(self::WX_USERINFO_URI, $this->get_access_token(), $this->get_rev_from());
        return $this->request($userinfo_url);
    }

    /**
     * 验证消息真实性
     */
    function valid() {
        $echostr = isset($_GET["echostr"]) ? $_GET["echostr"] : '';
        $signature = isset($_GET["signature"]) ? $_GET["signature"] : '';
        $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
        $nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : '';
        $this->log(var_export($_REQUEST, true), __FILE__, __LINE__);

        $arr = array($this->token, $timestamp, $nonce);
        sort($arr, SORT_STRING);
        $str = implode($arr);
        $_signature = sha1($str);

        if (trim($echostr) == '' && $_signature == $signature) {
            $this->log('valid success', __FILE__, __LINE__);
            return true;
        } else if (trim($echostr) != '' && $_signature == $signature) {
            $this->log('valid success', __FILE__, __LINE__);
            die($echostr);
        }
        $this->log('no access', __FILE__, __LINE__);
        die('no access');
    }

    /**
     * 接收消息：普通消息、事件推送消息
     */
    function get_rev_message() {
        $this->valid();
        $method = "";
        $rev_str = file_get_contents("php://input");
        $this->rev_message = (array) simplexml_load_string($rev_str, 'SimpleXMLElement', LIBXML_NOCDATA);
        $message = $this->rev_message;
        $this->log(var_export($message, true), __FILE__, __LINE__);
        if (isset($message['EventKey']) && trim($message['EventKey']) != '') {
            $event = strtolower($message['Event']);
            $event_key = strtolower($message['EventKey']);
            $method .= "_{$event}_{$event_key}";
        } else if (isset($message['Event']) && trim($message['Event']) != '') {
            $event = strtolower($message['Event']);
            $method .= "_{$event}";
        } else if (isset($message['MsgType']) && trim($message['MsgType']) != '') {
            $message_type = strtolower($message['MsgType']);
            $method .= "_{$message_type}";
        }
        $this->log($method, __FILE__, __LINE__);
        return $method;
    }

    /**
     * 获取 发送方帐号（用户openid）
     */
    function get_rev_from() {
        return isset($this->rev_message['FromUserName']) ? $this->rev_message['FromUserName'] : '';
    }

    /**
     * 获取 开发者微信号
     */
    function get_rev_to() {
        return isset($this->rev_message['ToUserName']) ? $this->rev_message['ToUserName'] : '';
    }

    /**
     * 回复消息 - 文本消息
     */
    function reply_text($text = "感谢您的支持") {
        $message = '<xml>' .
                '<ToUserName><![CDATA[' . $this->get_rev_from() . ']]></ToUserName>' .
                '<FromUserName><![CDATA[' . $this->get_rev_to() . ']]></FromUserName>' .
                '<CreateTime><![CDATA[' . time() . ']]></CreateTime>' .
                '<MsgType><![CDATA[text]]></MsgType>' .
                '<Content><![CDATA[' . $text . ']]></Content>' .
                '</xml>';
        $this->log($message, __FILE__, __LINE__);
        $this->reply($message);
    }

    /**
     * 回复消息 - 图片消息
     */
    function reply_image($media_id) {
        $message = '<xml>' .
                '<ToUserName><![CDATA[' . $this->get_rev_from() . ']]></ToUserName>' .
                '<FromUserName><![CDATA[' . $this->get_rev_to() . ']]></FromUserName>' .
                '<CreateTime><![CDATA[' . time() . ']]></CreateTime>' .
                '<MsgType><![CDATA[image]]></MsgType>' .
                '<Image>' .
                '<MediaId><![CDATA[' . $media_id . ']]></MediaId>' .
                '</Image>' .
                '</xml>';
        $this->log($message, __FILE__, __LINE__);
        $this->reply($message);
    }

    /**
     * 回复消息 - 图文消息
     */
    function reply_news($articles) {
        $message = '<xml>' .
                '<ToUserName><![CDATA[' . $this->get_rev_from() . ']]></ToUserName>' .
                '<FromUserName><![CDATA[' . $this->get_rev_to() . ']]></FromUserName>' .
                '<CreateTime><![CDATA[' . time() . ']]></CreateTime>' .
                '<MsgType><![CDATA[news]]></MsgType>' .
                '<ArticleCount><![CDATA[' . count($articles) . ']]></ArticleCount>' .
                '<Articles>';
        foreach ($articles as $i) {
            $message .= '<item>' .
                    '<Title><![CDATA[' . $i['title'] . ']]></Title>' .
                    '<Description><![CDATA[' . $i['description'] . ']]></Description>' .
                    '<PicUrl><![CDATA[' . $i['picurl'] . ']]></PicUrl>' .
                    '<Url><![CDATA[' . $i['url'] . ']]></Url>' .
                    '</item>';
        }
        $message .= '</Articles>';
        $message .= '</xml>';
        $this->log($message, __FILE__, __LINE__);
        $this->reply($message);
    }

    /**
     * 发送客服消息 - 文本消息
     */
    function custom_text($openid, $content) {
        static $flag = false;
        $arr = array(
            'touser' => $openid,
            'msgtype' => 'text',
            'text' => array('content' => $content),
        );
        $custom_text_url = self::WX_API_URL . sprintf(self::WX_CUSTOM_URI, $this->get_access_token());
        $this->log("custom_text_url: $custom_text_url", __FILE__, __LINE__);
        $data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $this->log("custom_text data: $data", __FILE__, __LINE__);
        $result = $this->request($custom_text_url, $data);
        $this->log("result: " . var_export($result, true), __FILE__, __LINE__);
        if (!$flag && isset($result['errcode']) && $result['errcode']) {
            $flag = true;
            $this->get_access_token(true);
            return $this->custom_text($openid, $content);
        }
        return $result;
    }

    /**
     * 发送客服消息 - 图文混排
     */
    function custom_news($openid, $articles) {
        static $flag = false;
        $arr = array(
            'touser' => $openid,
            'msgtype' => 'news',
            'news' => array('articles' => $articles),
        );
        $custom_news_url = self::WX_API_URL . sprintf(self::WX_CUSTOM_URI, $this->get_access_token());
        $this->log("custom_news_url: $custom_news_url", __FILE__, __LINE__);
        $data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $this->log("custom_news data: $data", __FILE__, __LINE__);
        $result = $this->request($custom_news_url, $data);
        $this->log("result: " . var_export($result, true), __FILE__, __LINE__);
        if (!$flag && isset($result['errcode']) && $result['errcode']) {
            $flag = true;
            $this->get_access_token(true);
            return $this->custom_news($openid, $articles);
        }
        return $result;
    }

    /**
     * 自定义菜单 - 创建菜单
     */
    function menu_create($menu) {
        static $flag = false;
        $menu_create_url = self::WX_API_URL . sprintf(self::WX_MENU_CREATE_URI, $this->get_access_token());
        $this->log("menu_create_url: $menu_create_url", __FILE__, __LINE__);
        $data = json_encode($menu, JSON_UNESCAPED_UNICODE);
        $this->log("custom_news data: $data", __FILE__, __LINE__);
        $result = $this->request($menu_create_url, $data);
        $this->log("result: " . var_export($result, true), __FILE__, __LINE__);
        if (!$flag && isset($result['errcode']) && $result['errcode']) {
            $flag = true;
            $this->get_access_token(true);
            return $this->menu_create($menu);
        }
        return $result;
    }

    /**
     * 自定义菜单 - 查询菜单
     */
    function menu_get() {
        static $flag = false;
        $menu_get_url = self::WX_API_URL . sprintf(self::WX_MENU_GET_URI, $this->get_access_token());
        $this->log("menu_get_url: $menu_get_url", __FILE__, __LINE__);
        $result = $this->request($menu_get_url);
        $this->log("result: " . var_export($result, true), __FILE__, __LINE__);
        if (!$flag && isset($result['errcode']) && $result['errcode']) {
            $flag = true;
            $this->get_access_token(true);
            return $this->menu_get();
        }
        return $result;
    }

    /**
     * 自定义菜单 - 删除菜单
     */
    function menu_del() {
        static $flag = false;
        $menu_del_url = self::WX_API_URL . sprintf(self::WX_MENU_DEL_URI, $this->get_access_token());
        $this->log("menu_del_url: $menu_del_url", __FILE__, __LINE__);
        $result = $this->request($menu_del_url);
        $this->log("result: " . var_export($result, true), __FILE__, __LINE__);
        if (!$flag && isset($result['errcode']) && $result['errcode']) {
            $flag = true;
            $this->get_access_token(true);
            return $this->menu_del();
        }
        return $result;
    }

}
