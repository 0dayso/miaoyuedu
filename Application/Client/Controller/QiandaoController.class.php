<?php
/**
 * 模块: 客户端
 *
 * 功能: 微信签到送代金券、红薯银币
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: QiandaoController.class.php 1473 2017-01-24 09:10:57Z guonong $
 */

namespace Client\Controller;

use Client\Common\Controller;

class QiandaoController extends Controller {
    var $userinfo = array();        //完整的用户信息，来源于数据库
    var $gid      = 0;                   //当前活动ID

    /**
     * 签到活动首页
     * @param 无
     * @return 无
     */
    public function indexAction() {
        $config = $this->getQiandaoConfig();
        $this->assign('config', $config);
        $this->display();
    }

    public function testsAction() {
        $tpl = I('tpl', '', 'trim');
        if (!$tpl) {
            $tpl = 'index';
        }
        $config = $this->getQiandaoConfig();
        $this->assign('config', $config);
        $msg    = '您的已经领完，<a href="' . url('User/index') . '">点此查看</a><br />请关注我们的其他活动';
        $this->assign('msg', $msg);
        //$this->assign('premsg', $msg);
		$this->assign('notend', rand(1,10) % 2);

		$this->assign('value', 10);
        $this->display('Qiandao/' . $tpl);
    }

    /**
     * 签到
     * @param 无
     * @return string 签到结果提示
     */
    public function openAction() {
        $notend = true;
        $gid    = $this->gid;
        $uid    = $this->userinfo['uid'];
        $lModel = new \Client\Model\WechatQiandaoModel();
        $config = $this->getQiandaoConfig();

        $available = $lModel->getAvailable($uid, $gid, $config);
        if ($available === false) {
            $this->opened($lModel->getError());
            return;
        }


        $this->assign('config', $config);

        $value = $lModel->getReward($config['min_num'], $config['max_num']);

        $result = $lModel->saveLog($uid, $gid, $value);
        if ($result === false) {
            $this->redirect('', '', 5, $lModel->getError());
        }
        //领取成功，计算是否最后一次
        array_walk($available, function(&$v) {
            $v++;
        });
        $notend = $available['today'] < $config['per_day'];
		$this->assign('notend', $notend);
        $this->assign('last_read_url', session('_last_read_page'));
        $this->assign('premsg', $premsg);
        $this->assign('value', $value);
        $this->assign('notend', $notend);
        $this->display();
    }

    /*
     * 签到函数
     */
    private function opened($msg = '', $showconfig = true) {
        if (IS_AJAX) {
            $output = array('status' => 0, 'info' => $msg, 'url');
            $this->ajaxReturn($output);
        }
        if ($showconfig) {
            $config = $this->getQiandaoConfig();
            $this->assign('config', $config);
        }
        $this->assign('last_read_url', session('_last_read_page'));
        $this->assign('msg', $msg);
        $this->display('Qiandao/opened');
        exit;
    }

    /**
     * 引导用户去关注微信公众号
     */
    public function nofocusAction() {
        $config = $this->getQiandaoConfig();
        $this->assign('config', $config);
        $this->display();
    }

    /**
     * 读取用户的签到记录
     */
    private function getUserLogs() {
        $map        = array();
        $map['gid'] = $this->gid;
        $map['uid'] = $this->userinfo['uid'];
    }

    /**
     * 礼包配置
     * 暂时写在代码里，数据库中还有许多规则没有考虑好，比如：针对的网站，针对的公众号等等
     * @return array
     */
    private function getQiandaoConfig() {
        $result = array(
            'begin_time' => '0000-00-00 00:00:00', //不限制起始时间
            'end_time'   => '0000-00-00 00:00:00', //不限制结束时间
            'min_num'    => 8, //代金券最小数量
            'max_num'    => 51, //代金券最大数量
            'per_day'    => 1, //每天可以签到次数
            'is_enabled' => 1, //是否开启此活动
            'wids'       => '4',
        );
        $today  = date('Y-m-d H:i:s', NOW_TIME);
        if ($result['begin_time'] > $today) {
            $this->redirect('', '', 3, '活动尚未开始，敬请期待！');
        }
        if ($result['end_time'] < $today && $result['end_time'] !== '0000-00-00 00:00:00') {
            $this->redirect('', '', 3, '活动已经结束！');
        }
        if (!$result['is_enabled']) {
            $this->redirect('', '', 3, '活动暂停！');
        }
		//2017年春节期间前三天每天允许签到2次
		//if(canTest()) {
		if(NOW_TIME>=1485446400 && NOW_TIME<=1485876600) {
			$result['per_day'] = 2;
		}
        return $result;
    }

    public function _initialize() {
        parent::_initialize();
        if (strtolower(ACTION_NAME) === 'nofocus' || strtolower(ACTION_NAME) === 'tests' || strtolower(ACTION_NAME) === 'getbooks') {
            return;
        }
        if (CLIENT_NAME !== 'html5') {
            _exit();
        }
        //每月最后一天的11:30以后，每月1号的0:30以前不允许执行以下操作！
        $day = date('d');
        $hour = date('H');
        $minute = date('i');
        if($day==1 && $hour==0 && $minute<30){
			$this->opened('</p><p style="font-size:12px;line-height: 21px;margin-left: 30px;margin-right: 30px;">由于每月底服务器维护，故每月最后一天23:30至次日0:30之间不能进行签到活动。</p><p>');
        }
        $begin = $end = 0;
        mk_time_xiangdui(NOW_TIME, 'thismonth', $begin, $end);
        if($day == date('d', $end)) {
            if($hour>=23 && $minute>=30) {
				$this->opened('</p><p style="font-size:12px;line-height: 21px;margin-left: 30px;margin-right: 30px;">由于每月底服务器维护，故每月最后一天23:30至次日0:30之间不能进行签到活动。</p><p>');
            }
        }
        //_exit('系统升级，请稍候再来！');
        $this->check_user_login();
        if (!session('uid') || ACTION_NAME == 'relogin') {
            //目前，U和url都不支持取回实际要操作的域名，只好这样写了！
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $refurl   = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $refurl   = C('TMPL_PARSE_STRING.__MOBDOMAIN__') . "/qiandao.do";
            //调用微信登录
            header("Content-Type: text/html; charset=utf-8");
            echo ''
            . '<script>'
            . ''
            . 'setTimeout(function(){document.wechatloginform.submit();},2000);'
            . ' '
            . '</script>'
            . '<form name="wechatloginform" action="' . C('USERDOMAIN') . '/third/wechat/login.do" method="post">'
            . '<input type="hidden" name="fu" value="' . rawurlencode($refurl) . '"/>'
            . '<input type="hidden" name="cookietime" value="7200"/>'
            . '<input type="hidden" name="iswap" value="1"/>'
            . '</form>';
            exit;
        }
        if (ACTION_NAME === 'open') {
            $str = basename($this->M_forward);
            $str = strtolower(str_replace(array('.html', '.do'), '', $str));
            if (strpos($str, '?')) {
                $str = substr($str, 0, strpos($str, '?'));
            }
            if ($str != 'qiandao' && $str != 'index') {
                redirect(url('index', '', 'do'));
            }
        }
        $this->userinfo = session();
        if (!isset($this->userinfo['openid']) || !isset($this->userinfo['ologin'])) {
            $uObject        = D('User');
            //后面的动作会用到openid和ologin
            $this->userinfo = array_merge($this->userinfo, $uObject->field('openid,ologin')->find($this->userinfo['uid']));
        }
        //检测是否已经关注指定的微信号,目前只能实现从微信登录用户的处理，所以，如果ologin不为4的话，openid就不能做为判断的依据
        if ($this->userinfo['ologin'] != '4' || !$this->userinfo['openid']) {
            //$this->opened('对不起，本活动目前只向微信登录用户开放。<br /><a href="' . url('User/login', array('fu' => url('Qiandao/index', '', 'do'))) . '">点我使用微信登录签到领取代金券</a>', false);
            $this->opened('对不起，本活动目前只向微信登录用户开放。<br /><a href="' . url('relogin', '', 'do') . '">点我使用微信登录签到领取代金券</a>', false);
        }
        //$config = $this->getQiandaoConfig();
    }

    /**
     * 页面中显示的小说列表，数据来源：女生周销售榜前15条，随机取3条
     * @param 无
     * @return array 女生周销售三条随机数组
     */
    function getBooksAction() {
        $searchObj = new \Client\Model\SearchModel();
        $sex       = cookie('sex_flag');
        if (!$sex) {
            $sex = C('DEFAULT_SEX');
        }
        $pclassidAry = array_keys(C('CATEGORY'));
        if ($sex == 'nan') {
            unset($pclassidAry[array_search(2, $pclassidAry, true)]);
        } else {
            $pclassidAry = array(2);
        }
        $pagesize = 15; //：一页取几条
        $res = $searchObj->getSearchResult('', 1, $pclassidAry, array(), 0, 0, 0, 0, $pagesize, array(), array(0, 1), 1, 'lastweek_salenum', array(), array(2, 3, 4, 5, 6, 7, 8), 1);
        if ($res) {
            $lists  = $res['bookinfo'];
            $result = array_rand($lists, 3);
            $res    = array();
            foreach ((array) $result as $key) {
                $lists[$key]['url'] = url('Book/view', array('bid' => $lists[$key]['bid']));
                $res[]              = $lists[$key];
            }
            unset($result); unset($lists);
        } else {
            $res = array();
        }
        $this->ajaxReturn($res);
    }

}
