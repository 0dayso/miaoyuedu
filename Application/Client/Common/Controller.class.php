<?php
/**
 * 模块: 三合一客户端
 *
 * 功能: 三合一控制器基类
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: Controller.class.php 1489 2017-02-09 10:12:46Z dingzi $
 */

namespace Client\Common;

use Think\App;

class Controller extends \HS\Controller {
    /**
     * 重载call,区分client名和版本,方法,分配到实际调用方法中
     * (non-PHPdoc)
     * @see Think.Controller::__call()
     */
    public function __call($method, $params) {
        $method                = str_replace(C('ACTION_SUFFIX'), '', $method);
        $call_method           = '_' . $method . '_' . CLIENT_NAME . '_' . str_replace('.', '_', CLIENT_VERSION);
        $default_client_method = '_' . $method . '_' . CLIENT_NAME; //指定客户端的方法
        $default_method        = '_' . $method;
        //指定客户端_版本的方法存在
        if (method_exists($this, $call_method)) {
            App::invokeAction($this, $call_method);
        }//指定客户端的方法存在
        elseif (method_exists($this, $default_client_method)) {
            App::invokeAction($this, $default_client_method);
        }//默认
        elseif (method_exists($this, $default_method)) {
            App::invokeAction($this, $default_method);
        }
        return true;
    }

    /**
     * 系统初始化
     */
    public function _initialize() {
        parent::_initialize();
        $this->pageTitle = '';
        $_authCode       = '';
        C('COOKIE_DOMAIN', str_replace(':8090', '', C('CLIENT.' . CLIENT_NAME . '.domain')));      // Cookie有效域名
        ini_set('session.cookie_domain', C('COOKIE_DOMAIN'));
        if (CLIENT_NAME == 'ios' || CLIENT_NAME == 'android') {
            session('[start]');
        }
        $uuid            = $device          = $phone           = '';
        $wechatInstalled = false;
        if (CLIENT_NAME === 'android') {
            list($uuid, $device, $phone) = android_get_uuid_device();
            $wechatInstalled = false;
            //一些初始化的参数：
            if (!isset($_GET['sex_flag']) && isset($_GET['P31'])) {
                $_GET['sex_flag'] = strtolower(I('P31', '', 'trim'));
            }
            $_authCode = I('P30', '', 'trim');
        } else if (CLIENT_NAME === 'ios') {
            $uuid            = I('P29', '', 'trim');       //UUID
            $device          = I('P28', '', 'trim');     //设备名称
            $phone           = '';
            $wechatInstalled = I('P31/d');      //是否已经安装了微信客户端
            $_authCode       = I('P30', '', 'trim');
        } else if (CLIENT_NAME === 'html5') {
            if (isInWechat()) {
                $wechatJsSDK = new JSSDK(C('wechatwapconfig.APPID'), C('wechatwapconfig.APPSECRET'));
                $wechatSign  = $wechatJsSDK->GetSignPackage();
                $this->assign('wechatSign', $wechatSign);
            }
        }
        //阅读偏好
        $sex_flag = I('sex_flag', '', 'trim');
        if (!$sex_flag) {
            //如果没有设置，则读取COOKIE
            $sex_flag = cookie('sex_flag');
            //如果COOKIE中依然没有设置，则随机取一个
            if (!$sex_flag && CLIENT_NAME !== 'html5') {
                $sex_flag = array_rand(array('nan' => 'nan', 'nv' => 'nv'));
            }
        }
        if ($sex_flag) {
            $sex_flag         = ($sex_flag === 'nan') ? $sex_flag : 'nv';
            $_GET['sex_flag'] = $sex_flag;      //设置，以便后续的代码中需要读取
        }
        //cookie('sex_flag', $sex_flag);      //设置COOKIE，以便后面的页面中依然没有设置sex_flag
        $this->assign('sex_flag', $sex_flag);
        $this->assign('deviceinfo', $this->deviceInfo);
        $this->assign('userinfo', array());
        if (CLIENT_NAME === 'android' || CLIENT_NAME === 'ios') {
            if (!$uuid) {
                $uuid = cookie(CLIENT_NAME . '_uuid');
            }
            if (!$device) {
                $device = cookie(CLIENT_NAME . '_device');
            }
            if (!$wechatInstalled) {
                $wechatInstalled = cookie(CLIENT_NAME . '_wechatInstalled');
            }
            if ($uuid) {
                cookie(CLIENT_NAME . '_uuid', $uuid);
            }
            if ($device) {
                cookie(CLIENT_NAME . '_device', $device);
            }
            if ($wechatInstalled) {
                cookie(CLIENT_NAME . '_wechatInstalled', $wechatInstalled);
            }
            $this->deviceInfo = array(
                'UUID'            => $uuid,
                'device'          => $device,
                'wechatInstalled' => $wechatInstalled
            );
            if (ACTION_NAME !== 'login' && ACTION_NAME !== 'iflogin') {
                if(CLIENT_VERSION >= '2.0.0'){
                    $this->check_user_login($_authCode,$this->deviceInfo['UUID']);
                }else{
                    $this->check_user_login($_authCode);
                }
            }
            //这里处理支付的回传标志，有这个标志的话，通常是两个页面，不是首页，就是支付页面！
            if (isset($_GET['fu']) && $_GET['fu'] === 'gotofu') {
                unset($_GET['client']);
                $_GET['fu'] = '/';      //来源地址
                if (CLIENT_NAME === 'android') {
                    if (strtolower(CONTROLLER_NAME) === 'pay') {
                        //点了继续充值的话，为了防止用户后退，直接新开一个页面打开充值页面
                        $url = curPageUrl(1) . url('', $_GET);
                        doClient('open_child_webview', array('Url' => $url));
                    } else {
                        //返回，那就直接关闭这个充值窗口了
                        doClient('close_child_webview');
                    }
                }
            }
        }
        if ($this->M_forward == '/') {
            //首页
            $this->M_forward = url('Client/Index/index');
        } else {
            //这里处理一下，反编译出相应的模块，控制器，动作，然后依此来判断！
            $m = strtolower($this->M_forward);
            if ($m == strtolower(url('User/login')) || $m == strtolower(url('User/ChangeAccount'))) {
                $this->M_forward = url('User/index');
            }
        }
        if (!$this->M_forward) {
            $this->M_forward = url('Index/index');
        }
        //将ThinkPHP的路由规则解析成JS可以识别的格式
        $router   = C('URL_ROUTE_RULES');
        $jsRouter = array();
        if (C('URL_ROUTER_ON') && $router) {
            foreach ($router as $k => $v) {
                $jsRouter[$v] = _parseJsRouter($k);
            }
        }
        $this->assign('jsRouter', $jsRouter);
        //资源文件版本
        if (!C('SOURCE_VER')) {
            C('SOURCE_VER', '1.0');
        }
        //缓存KEY前缀
        if (!C('CACHE_PREFIX')) {
            C('CACHE_PREFIX', 'txtxiaoshuo');
        }
        //默认的样式
        $style = '';
        //if (CLIENT_NAME === 'html5') {
            if ($sex_flag = I('sex_flag', '', 'trim,strtolower')) {
                if (in_array($sex_flag, array('nan', 'nv'))) {
                    $style = $sex_flag;
                }
            }
        //}
        if (!$style) {
            if($style = C('DEFAULT_STYLE')){
                $this->assign('style', $style);
            }
        }
    }

    /**
     * 系统空操作的统一处理
     * @param type $act
     * @param type $para
     */
    public function _empty($act, $para) {
        if (IS_AJAX) {
            $this->error('指定的页面未找到！');
        } else {
            //检查一下有没指定的模板
            $templateFile = $this->view->parseTemplate($templateFile);
            if (is_file($templateFile)) {
                $this->display($templateFile);
            } else {
                if (APP_DEBUG) {
                    echo $act;
                    pre($para);
                } else {
                    _exit();
                }
            }
        }
    }

    /**
     * 检测用户是否已经登录
     * 在IOS和Android环境会根据P30参数直接启动，M站需要手动启动
     * @param mixed $authcode
     */
    public function check_user_login($authcode = '',$uuid = '') {
        if(!isInWechat()) {
            noCachePage();
        }
        session('[start]');
		if(CLIENT_NAME=='android' || CLIENT_NAME=='ios') {
			if(!$authcode) {
				$authcode = I('P30', '', 'trim');
			}
		}
        $uModel = new \Client\Model\UserModel();
        if ($authcode || !session('islogin')) {
            if (!$authcode) {
                $cookie = cookie('authcookie');
            } else {
                $cookie = $authcode;
            }
            if (!empty($cookie)) {
                if(CLIENT_VERSION >= '2.0.0' && strtolower(CLIENT_NAME) === 'ios'){
                    if(!$uuid){
                        $this->deviceInfo['UUID'];
                    }
                    //2.0.0起加上uuid
                    list($uid, $password, $third) = $uModel->decode_authcode($cookie,$uuid);
                }else {
                    list($uid, $password, $third) = $uModel->decode_authcode($cookie);
                }
                if (CLIENT_VERSION < '2.0.0' && $third && strlen($third) == 32) {
                    $username = $password;
                    $password = $third;
                }
                if ($uid && !empty($password)) {
                    $row = $uModel->find($uid);
                    if ($row && is_array($row) && $row['is_deleted'] != 1 && $row['is_locked'] != 1) {
                        if (md5($row['password']) == $password) {
                            $result = $uModel->login($row, 0);
                            if ($result === false) {
                                exit($uModel->getError());
                            }
                        }
                    } else if ($row['is_deleted'] || $row['is_locked']) {
                        $uModel->doLogout();
                        if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0'){
                            doClient('User/login');
                        }else{
                            doClient('saveP30', array('P30' => '', 'message' => 'fuck'), false);
                            $msg = '对不起，您的帐号已经被封禁，请和客服联系！' . encode10to64($uid);
                            if (!$authcode) {
                                $this->error($msg);
                            }    
                        }
                    }
                }
            }
        } else {
            $uid    = session('uid');
            //检测串号！
            $token_nickname = cookie('nickname');
            if ($token_nickname && $token_nickname != session('nickname')) {
                $this_nickname = session('nickname');
                $logsobj = new \Client\Model\SystemlogsModel();
                $sessionid = session_id();
                $cookieid = cookie(session_name());
                $chglog = '账号串号:' . $token_nickname . ',' . $this_nickname . ',sid=' . $sessionid . ',cookieid=' . $cookieid.',url='.$_SERVER['REQUEST_URI'];
                $data = array(
                    'toid'        => session('uid'),
                    'toname'      => '',
                    'chglog'      => $chglog,
                    'chgtype'     => 9,  
                    'runprograme' => $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
                $logsobj->addSyslog($data);
                $uModel->doLogout();
                if(CLIENT_NAME=='ios' || CLIENT_NAME=='android') {
                    if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0'){
                        doClient('User/login');
                    }else{
                        doClient('saveP30');
                    }

                }
                return;
            }

            $result = $uModel->login($uid, 0);
            if ($result === false) {
                $this->error($uModel->getError());
            }
        }
        $this->assign('userinfo', session());
    }

}

if (!function_exists('encode10to64')) {
    function encode10to64($dec) {
        $base   = '0123456789:;abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //$base='.。,、！？：；`﹑•＂^…‘’“”〝〞~\∕|¦‖—﹐﹕﹔！？﹖﹏＇ˊ-﹫︳_＿￣¯︴@―ˋ´﹋﹌¿¡;︰¸﹢﹦﹤­˜﹟﹩﹠﹪﹡﹨';
        $result = '';

        do {
            $result = $base[$dec % 64] . $result;
            $dec    = intval($dec / 64);
        } while ($dec != 0);

        return $result;
    }

}

if (!function_exists('curPageUrl')) {
    /**
     * 获取当前页面的URL
     *
     * @param int $type
     * @return 当前页面的URL
     */
    function curPageUrl($type = 0) {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["HTTP_HOST"];
        }
        if ($type === 0) {
            $pageURL .= $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}
function _parseJsRouter($str) {
    $str = preg_replace('@:([^/]+)@i', '[\\1]', $str);
    return $str;
}


class JSSDK {
    private $appId;
    private $appSecret;
    private $error_filename;
    private $tocken_key;
    private $jsapi_key;

    public function __construct($appId, $appSecret) {
        $this->appId          = $appId;
        $this->appSecret      = $appSecret;
        $this->tocken_key     = 'WECHAT_JSAPI_ACCESS_TOKEN_' . $this->appId;
        $this->jsapi_key      = 'WECHAT_TICKET_KEY_' . $this->appId;
        $this->error_filename = TEMP_PATH . '/jsapi_error.json';
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();
        if ($jsapiTicket === -1) {
            //正在刷新中
            return array();
        }
        if ($jsapiTicket === false) {
            $jsapiTicket = $this->getJsApiTicket();
        }
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url      = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr  = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"        => $this->appId,
            "nonceStr"     => $nonceStr,
            "timestamp"    => $timestamp,
            "url"          => $url,
            "signature"    => $signature,
            "rawString"    => $string,
            'jsapi_ticket' => $jsapiTicket
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        global $M_redis;
        if (!$M_redis) {
            $M_redis = new \Think\Cache\Driver\Redis(C('rdconfig'));
        }
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = $M_redis->get($this->jsapi_key);
        if (!$data || $data['expire_time'] < time()) {
            //TODO 这里需要分析一下并发的问题！
            $lock = new \HS\CacheLock('GET_JSAPI_KEY' . $this->appId);
            if (!$lock->lock()) {
                return -1;
            }
            $accessToken = $this->getAccessToken();
            $url         = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=jsapi';
            $ticket      = $this->httpGet($url);
            //echo $ticket;
            $res         = json_decode($ticket);
            $ticket      = $res->ticket;
            if ($ticket) {
                $data = array(
                    "expire_time"  => time() + 7000,
                    "jsapi_ticket" => $ticket);
                $M_redis->set($this->jsapi_key, $data, 7000);
            } else {
                $fp = fopen($this->error_filename, 'w');
                fwrite($fp, json_encode($res));
                fclose($fp);
            }
            $lock->unlock();
        } else {
            $ticket = $data['jsapi_ticket'];
        }
        //获取失败，清除accessToken，重试
        if (!$ticket) {
            $M_redis->rm($this->tocken_key);
            return false;
        }

        return $ticket;
    }

    private function getAccessToken() {
        global $M_redis;
        if (!$M_redis) {
            $M_redis = new \Think\Cache\Driver\Redis(C('rdconfig'));
        }
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = $M_redis->get($this->tocken_key);
        //不存在或者已过期
        if (!$data || $data['expire_time'] <= time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appId . "&secret=" . $this->appSecret;
            $res          = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data = array(
                    'expire_time'  => time() + 7000,
                    'access_token' => $access_token);
                $M_redis->set($this->tocken_key, $data, 7000);
            }
        } else {
            $access_token = $data['access_token'];
        }
        return $access_token;
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

}
