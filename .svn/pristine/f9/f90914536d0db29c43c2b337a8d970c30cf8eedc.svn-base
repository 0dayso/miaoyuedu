<?php

namespace HS;

class Controller extends \Think\Controller {

    /**
     * 页面标题
     * @var string
     */
    public $pageTitle = '';
    /**
     * 快捷链接
     * @var array
     */
    public $quickLink = array();
    /**
     * 跳转网址
     * @var string
     */
    public $M_forward = '';
    /**
     * 设备信息
     * @var array
     */
    public $deviceInfo = array();

    /**
     * 空操作
     * @param unknown $act
     * @param unknown $para
     */
    public function _empty($act, $para) {
        if (IS_AJAX) {
            $this->error('指定的页面未找到！');
        } else {
            $this->display();
        }
    }

    /**
     * 初始化
     */
    protected function _initialize() {
        global $M_redis;
        $M_redis = new \HS\MemcacheRedis();
        //判断来路网址
        if (strtolower(C('ACTION_NAME')) === 'thirdlogin' || strtolower(C('CONTROLLER_NAME'))=='thirdlogin') {
            $fu = cookie('fu');
            if(!$fu) {
                $fu = I('fu', '', 'trim,rawurldecode');
            }
        } else {
            $fu = I('fu', '', 'trim,rawurldecode');
            $M_referer = I('M_referer', '', 'trim');
            if (empty($M_referer) && isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
                $M_referer = preg_replace("/([\?&])((sid\=[a-z0-9]{6})(&|$))/i", '\\1', $GLOBALS['_SERVER']['HTTP_REFERER']);
                $M_referer = substr($M_referer, -1) == '?' ? substr($M_referer, 0, -1) : $M_referer;
            }
            if ($fu === '' && $M_referer && !strpos(strtolower($M_referer), 'login')) {
                $fu = $M_referer ? $M_referer : url('User/index', '', 'do');
            }
            if(!$fu){
                $fu = url('User/index', '', 'do');
            }
        }
        //header('fu is:'.$fu);
        if ($fu === 'http:' || strpos(strtolower($fu), 'logout') || strpos(strtolower($fu), 'login')) {
            $fu = url('User/index', '', 'do');
        }
        if ($fu && $fu!='/' && $fu!='webview' && substr($fu, 0, 4) != 'http') {
            $fu = (defined('ROOT_URL')?ROOT_URL:C('TMPL_PARSE_STRING.__MOBDOMAIN__')).((substr($fu, 0, 1) == '/') ? $fu : "/" . $fu);
        }
        $fu = preg_replace("/<([a-zA-Z]+)[^>]*>.*/", '', $fu);
        $this->M_forward = removeXSS(rawurldecode($fu));
    }
    /**
     * 关闭时自动关闭redis,phpredis的bug
     */
    public function __destruct(){
        global $M_redis;
        if(is_object($M_redis)) {
	    $M_redis->getredisObj()->close();
        }
    }
    
    /**
     * 模板显示 调用内置的模板引擎显示方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     */
    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
        \Think\Db::closeAll();
        $sid = I('fromsid', 0, 'intval');
        if ($sid) {
            $this->assign('fromsid', $sid);
        }
        if ($this->pageTitle) {
            $this->assign('pageTitle', $this->pageTitle);
        }
        $this->assign('deviceInfo', $this->deviceInfo);
        $this->assign('M_forward', $this->M_forward);
        if ($this->quickLink) {
            if (isset($this->quickLink['form']) && $this->quickLink['form']) {
                $this->assign('quickForm', $this->quickLink['form']);
                unset($this->quickLink['form']);
            }
            $selfUrl = U();
            foreach ($this->quickLink as $k => &$v) {
                if (isset($v['sub']) && $v['sub']) {
                    foreach ($v['sub'] as $kk => &$vv) {
                        if ($vv['hidden']) {
                            unset($v['sub'][$kk]);
                        } elseif ($vv['link'] == $selfUrl) {
                            $vv['class'] = 'disabled';
                            $vv['link'] = '#';
                        }
                    }
                    if (isset($vv['confirm'])) {
                        $vv['confirm'] = str_replace('"', "'", $vv['confirm']);
                        $vv['confirm'] = str_replace("'", "\'", $vv['confirm']);
                    }
                    if (!$v['sub']) {
                        unset($this->quickLink[$k]);
                    }
                } else {
                    if (isset($v['hidden']) && $v['hidden']) {
                        unset($this->quickLink[$k]);
                    } elseif ($v['link'] == $selfUrl) {
                        $v['class'] = 'disabled';
                        $v['link'] = '#';
                    }
                }
                if (isset($v['confirm'])) {
                    $v['confirm'] = str_replace('"', "'", $v['confirm']);
                    $v['confirm'] = str_replace("'", "\'", $v['confirm']);
                }
            }
            $this->assign('quickLink', $this->quickLink);
        }
        //一些常变量
        $this->assign('yesorno', array('1' => '是', '0' => '否'));
        $this->assign('sexlist', array('0' => '保密', '1' => '男', '2' => '女'));

        parent::display($templateFile, $charset, $contentType, $content, $prefix);
    }

    /**
     * Action跳转(URL重定向） 支持指定模块和延时跳转
     * @access protected
     * @param string $url 跳转的URL表达式
     * @param array $params 其它URL参数
     * @param integer $delay 延时跳转的时间 单位为秒
     * @param string $msg 跳转提示信息
     * @return void
     */
    protected function redirect($url, $params = array(), $time = 0, $msg = '') {
        if(substr($url, -3)!=='.do' && substr($url, -5)!=='.html') {
            $url = url($url, $params);
        }
        @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        @header("Cache-Control: no-cache");
        @header("Pragma: no-cache");

        if(!$time && !$msg){
            if (!headers_sent()) {
                if (0 === $time) {
                    header('Location: ' . $url);
                } else {
                    header("refresh:0;url={$url}");
                }
                exit();
            } else {
                $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
                exit($str);
            }
        } else {
            $this->dispatchJump($msg, 0, $url);
        }
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function error($message = '', $jumpUrl = '', $ajax = false) {
        $this->dispatchJump($message, 0, $jumpUrl, $ajax);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function success($message = '', $jumpUrl = '', $ajax = false) {
        $this->dispatchJump($message, 1, $jumpUrl, $ajax);
    }

    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
    private function dispatchJump($message, $status = 1, $jumpUrl = '', $ajax = false) {
        C('TMPL_ENGINE_TYPE', 'think');
        C('DEFAULT_THEME', '');
        if (true === $ajax || IS_AJAX) {// AJAX提交
            $data = is_array($ajax) ? $ajax : array();
            $data['info'] = $message;
            $data['status'] = $status;
            $data['url'] = $jumpUrl;
            $this->ajaxReturn($data);
        }
        if (is_int($ajax)) {
            $this->assign('waitSecond', $ajax);
        } else {
            $this->assign('waitSecond', 3);
        }
        if (!empty($jumpUrl)) {
            $this->assign('jumpUrl', $jumpUrl);
        }
        // 提示标题
        $this->assign('msgTitle', $status ? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
        //如果设置了关闭窗口，则提示完毕后自动关闭窗口
        if ($this->get('closeWin')) {
            $this->assign('jumpUrl', 'javascript:window.close();');
        }
        $this->assign('status', $status);   // 状态
        //保证输出不受静态缓存影响
        C('HTML_CACHE_ON', false);
        if ($status) { //发送成功信息
            $this->assign('message', $message); // 提示信息
            // 成功操作后默认停留1秒
            if (!isset($this->waitSecond)) {
                $this->assign('waitSecond', '1');
            }
            // 默认操作成功自动返回操作前页面
            if (!isset($this->jumpUrl)) {
                $this->assign("jumpUrl", $_SERVER["HTTP_REFERER"]);
            }
            $this->display(C('TMPL_ACTION_SUCCESS'));
        }else {
            //\Think\Log::write($message, 'ERROR', '', LOG_PATH . 'SYSTEM_ERROR');
            $this->assign('message', $message); // 提示信息
            //发生错误时候默认停留3秒
            if (!isset($this->waitSecond)) {
                $this->assign('waitSecond', '3');
            }
            // 默认发生错误的话自动返回上页
            if (!isset($this->jumpUrl)) {
                $this->assign('jumpUrl', "javascript:history.back(-1);");
            }
            $this->display(C('TMPL_ACTION_ERROR'));
        }
        // 中止执行  避免出错后继续执行
        \Think\Db::closeAll();
        exit;
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data,$type='',$json_option=0) {
        if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
        $fn = '/data/server/www/www.yanqingkong.com/market.log';
        $market = '';
        if(file_exists($fn)){
            $market = file_get_contents($fn);
        } else {
            $market = 'first';
        }
        if(!$market) {
            $market = 'main';
        }
        $market = ' at '.$market;
        header('X-Powered-By:HongShu.com'.$market);
        \Think\Db::closeAll();
        if(is_array($data) && isset($data['info']) && !isset($data['message'])){
            $data['message'] = $data['info'];
        } else if (is_string($data)){
            $data = array(
                'message' => $data,
                'status' => 0
            );
        }
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data,$json_option).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            case 'YQK':
                global $ajax_return;
                $ajax_return = $data;
                break;
            default     :
                // 用于扩展其他返回格式数据
                \Think\Hook::listen('ajax_return',$data);
        }
    }

    /**
     * 显示验证码
     * @param boolean $useHz 是否使用汉字验证码
     */
    public function Verify($useHz = false) {
        $verify = new \HS\Verify(array('fontSize' => 25, 'length' => 4, 'imageH' => 30, 'imageW' => 120));
        if ($useHz) {
            $verify->useZh = true;
        }
        $verify->entry(1);
    }

}
