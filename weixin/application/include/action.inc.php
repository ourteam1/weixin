<?php

if (!defined('IN_MSAPP')) {
    exit('Access Deny!');
}

class Action extends App
{

    public $user_id   = null;
    public $wx_openid = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 加载模版文件
     */
    public function load_view()
    {
        include_once LIBS_DIR . 'view.class.php';
        return new View();
    }

    public function load_http()
    {
        include_once LIBS_DIR . 'http.class.php';
        return new Http();
    }

    /**
     * 加载数据库
     */
    public function load_db()
    {
        include_once LIBS_DIR . 'pdo.class.php';
        $dbh = new DB_PDO(DB_DSN, DB_USER, DB_PASSWORD);
        $dbh->set_table_prefix(DB_TABLE_PREFIX);
        $dbh->set_options(array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        return $dbh;
    }

    /**
     * 加载微信公共接口sdk库
     */
    public function load_weixin()
    {
        if (!class_exists('WeiXin')) {
            include_once LIBS_DIR . 'weixin.class.php';
        }
        $options = array(
            'token'        => WX_TOKEN,
            'appid'        => WX_APPID,
            'appsecret'    => WX_APPSECRET,
            'log_callback' => 'logger',
            'debug'        => true,
        );
        return new WeiXin($options);
    }

    /**
     * 加载微信推送消息公共接口sdk库
     */
    public function load_wxoauth()
    {
        if (!class_exists('WXOAuth')) {
            include_once LIBS_DIR . 'weixin.class.php';
        }
        $options = array(
            'appid'        => WX_APPID,
            'appsecret'    => WX_APPSECRET,
            'log_callback' => 'logger',
            'debug'        => true,
        );
        return new WXOAuth($options);
    }

    /**
     * 微信认证
     */
    public function wxauth()
    {
        // 如果是调试模式，直接返回
        if (defined('MSDEBUG') && MSDEBUG) {
            logger('DEBUG 模式！' . var_export(MSDEBUG, true), __FILE__, __LINE__);
            $this->user_id = '1';
            return true;
        }

        // 如果有session
        if (isset($_SESSION['user_id']) && trim($_SESSION['user_id']) != '' && isset($_SESSION['wx_openid']) && trim($_SESSION['wx_openid']) != '') {
            logger('session uid：' . $_SESSION['user_id'], __FILE__, __LINE__);
            logger('session wx_openid：' . $_SESSION['wx_openid'], __FILE__, __LINE__);
            $this->user_id   = $_SESSION['user_id'];
            $this->wx_openid = $_SESSION['wx_openid'];
            return true;
        }

        // 微信授权访问，并获取用户信息
        $userinfo = $this->wxoauth->get_userinfo();
        logger('微信授权用户信息：' . var_export($userinfo, true), __FILE__, __LINE__);
        if (!$userinfo) {
            die('no access');
        }

        // 查找用户
        $user = $this->db->where('wx_openid', $userinfo['openid'])->row('user');
        if ($user) {
            $this->user_id   = $_SESSION['user_id']   = $user['user_id'];
            $this->wx_openid = $_SESSION['wx_openid'] = $userinfo['openid'];
            return true;
        }

        // 添加用户
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
        logger('添加用户信息：' . var_export($data, true), __FILE__, __LINE__);
        $res = $this->db->insert('user', $data);
        if ($res === false) {
            logger('添加用户信息失败!!!', __FILE__, __LINE__);
            die('no access');
        }
        $user_id = $this->db->last_insert_id();
        logger('添加用户信息成功，用户编号：' . $user_id, __FILE__, __LINE__);
        $this->user_id   = $_SESSION['user_id']   = $user_id;
        $this->wx_openid = $_SESSION['wx_openid'] = $userinfo['openid'];
        return true;
    }

    public function error_message($error_message, $location = '')
    {
        header('Content-type:text/html;chartset=utf-8');
        if (!$location) {
            $location = 'history.go(-1)';
        } else {
            $location = 'location.href="' . site_url($location) . '"';
        }
        echo "<script>alert('$error_message');$location</script>";
        die;
    }

    public function success_message($success_message, $location = '')
    {
        header('Content-type:text/html;chartset=utf-8');
        if (!$location) {
            $location = 'history.go(-1)';
        } else {
            $location = 'location.href="' . site_url($location) . '"';
        }
        echo "<script>alert('$success_message');$location</script>";
        die;
    }
}
