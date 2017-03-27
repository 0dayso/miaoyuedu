<?php
/**
 * 模块: 客户端
 *
 * 功能: 所有ajax请求
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: dingzi
 * @version: $Id: UserajaxController.class.php 1585 2017-03-24 02:37:33Z guonong $
 */

namespace Client\Controller;

use Client\Common\Controller;
use Client\Model\UserModel;

class UserajaxController extends Controller {
    public function _initialize() {
        parent::_initialize();
        $this->check_user_login();
    }

    /**
     * 通过json方式获取用户session登录状态,或根据cookie登陆后返回session登录状态
     */
    public function _checklogin() {
        $out = array('islogin' => false, 'uid' => 0, 'nickname' => '游客');
        $uid = isLogin();
        if ($uid) {
            $out           = session();
            $out['avatar'] = getUserFaceUrl($uid, 'middle');
        }
        $this->ajaxReturn($out);
    }

    /**
     * android/IOS登录
     *
     * @param string $userName 账号 post
     * @param string $passWord 密码 post
     *
     * @return 成功/失败的信息
     */
    public function _login() {
        $output   = array("status" => 0, "message" => "", "url" => "");
        //用户名
        $userName = I("post.username", "", "trim");
        if (!$userName) {
            $output['message'] = "请输入账号或手机号";
            $this->ajaxReturn($output);
        }
        //密码
        $passWord = I("post.password", "", "trim");
        if (!$passWord) {
            $output['message'] = "密码不能为空";
            $this->ajaxReturn($output);
        }
        //验证码
        $imgCode = I('post.yzm',0,'intval');
        if(!$imgCode){
            $output['message'] = '验证码不能为空';
            $this->ajaxReturn($output);
        }
        if($imgCode != intval(session('IMGCODE'))){
            $output['message'] = '验证码错误';
            $this->ajaxReturn($output);
        }
        //手机号登录
        //$frommobile = I("post.frommobile",0,"intval");
        //是否记录登录状态
        $rememberLogin = I("post.remember", 0, "intval");
        if ($rememberLogin) {
            $remember = 1;
        } else {
            $remember = 0;
        }
        $userModel = new \Client\Model\UserModel();
        //手机登录
        if (isValidPhone($userName)) {
            $result = $userModel->loginByUsernamePassword($userName, $passWord, $remember, 3);
        }
        if (!$result) {
            //账号
            $result = $userModel->loginByUsernamePassword($userName, $passWord, $remember, 5);
        }
        if ($result) {
            $output       = $result;
            $output['fu'] = $this->M_forward == 'webview' ? url('User/index', array(), 'do') : (removeXSS($this->M_forward) ? : '/');
            $output['url'] = $this->M_forward == 'webview' ? url('User/index', array(), 'do') : (removeXSS($this->M_forward) ? : '/');
        } else {
            $output['message'] = "登录失败:".$userModel->getError().',username='.$userName;
        }
        $this->ajaxReturn($output);
    }

    /**
     * m站登录
     *
     * @param string $userName 账号 post
     * @param string $passWord 密码 post
     *
     * @return 登录成功/失败信息
     */
    public function _login_html5() {
        $output   = array("status" => 0, "message" => "", "url" => "");
        $userName = I("post.username", "", "trim");
        if (!$userName) {
            $output['message'] = "用户名不能为空";
            $this->ajaxReturn($output);
        }
        $passWord = I("post.password", "", "trim");
        if (!$passWord) {
            $output['message'] = "密码不能为空";
            $this->ajaxReturn($output);
        }
        //红薯加了验证码
        if(strtolower(CLIENT_NAME) !== 'yqm'){
            $imgCode = I('post.yzm',0,'intval');
            if(!$imgCode){
                $output['message'] = '验证码不能为空';
                $this->ajaxReturn($output);
            }
            if($imgCode != intval(session('IMGCODE'))){
                $output['message'] = '验证码错误';
                $this->ajaxReturn($output);
            }    
        }
        //记住密码
        $remPassword = I("post.remember", "", "trim");
        $userModel   = new \Client\Model\UserModel();
        //手机登录
        if (isValidPhone($userName)) {
            $result = $userModel->loginByUsernamePassword($userName, $passWord, $remPassword, 3);
        }
        if (!$result) {
            //账号
            $result = $userModel->loginByUsernamePassword($userName, $passWord, $remPassword, 5);
        }
        if ($result) {
            if (strstr($this->M_forward, url("User/register")) || strstr($this->M_forward, url("User/login"))) {
                $this->M_forward = '/';
            }
            $output['url']     = removeXSS($this->M_forward);
            $output['status']  = 1;
            $output['message'] = "登录成功";
        } else {
            $output['message'] = '登录失败,请重试';
        }
        $this->ajaxReturn($output);
    }
	public function _login_wap(){
		$this->_login_html5();
	}
    /**
     * yqm登录
     */
    public function _login_yqm() {
        $this->_login_html5();
    }

    public function _login_chushou(){
        $this->_login_html5();
    }
    /**
     * 手机注册:获取验证码
     *
     * @param int $mobileId 手机号码 post
     * @param int $vcode 验证码 post
     *
     * @return json 成功/失败消息
     */
    public function _verifyCode() {
        $output = array('status' => 0, 'message' => '', 'url' => '');
        if (IS_POST) {
            $this->check_user_login();
            //短信发送间隔时间
            $ip         = get_client_ip();
            S(C("rdconfig"));
            $mobregname = "mobreg_" . $ip;
            $badtime    = S($mobregname);
            if ($badtime) {
                $output['message'] = '您发短信的间隔时间太短,请稍后再发';
                $this->ajaxReturn($output);
            }
            //手机号码
            $mobile = I('post.mobileId', 'intval');
            //图片验证码
            $imgcode = I('post.sbm', 0, 'intval');
            if (!$imgcode) {
                $output['message'] = '请输入验证码';
                $this->ajaxReturn($output);
            }
            if (intval(session('imgcode')) != $imgcode) {
                if(strtolower(CLIENT_NAME) == 'ios' && CLIENT_VERSION >= '2.0.0'){
                    $cacheModel = new \HS\MemcacheRedis();
                    $key = ':imgcode:'.$mobile;
                    $vericode = $cacheModel->getMc($key);
                    if(!$vericode || $vericode != $imgcode){
                        $output['message'] = '验证码错误';
                        $this->ajaxReturn($output);
                    }else{
                        $cacheModel->delMc($key);
                    }
                }else{
                    $output['message'] = '验证码错误';
                    $this->ajaxReturn($output);
                }
            }
            //验证手机号
            if ($mobile) {
                $isvalidmobile = isValidPhone($mobile);
                if (!$isvalidmobile) {
                    $output['message'] = '手机号码错误';
                    $this->ajaxReturn($output);
                }
            } else {
                $output['message'] = '请输入手机号码';
                $this->ajaxReturn($output);
            }
            $userModel = new \Client\Model\UserModel();
            $type      = I("post.type", "", "trim");
            if ($type && $type == "losepwd") {
                /* 忘记密码，检测是否绑定手机 */
                $mobileinfo = $userModel->getUserByPhone($mobile);
                if (!$mobileinfo) {
                    $output['message'] = '您输入的手机号未绑定' . C('SITECONFIG.SITE_NAME') . '账号,请输入其他已绑定的手机号';
                    $this->ajaxReturn($output);
                }
            } else {
                /* 注册、绑定手机 */
                $illegalmob = $userModel->checkUserNameExist($mobile);
                if ($illegalmob) {
                    $output['message'] = '您输入的手机号已注册' . C('SITECONFIG.SITE_NAME') . '账号,请输入其他未注册的手机号';
                    $this->ajaxReturn($output);
                }
            }
            //默认密码
            $password   = mt_rand(100000, 999999);
            $pwd        = $userModel->pwdEncrypt($password);
            //过期时间
            $endtime    = 60 * 15;
            //是否已经发送验证信息,ELECT mobileid,vcode,vtime FROM wis_mobilevcode WHERE mobileid=\''.$mobileId.'\' ORDER by vtime DESC limit 1
            $verimap    = array("mobileid" => $mobile);
            $verifyinfo = M('mobilevcode')->field('mobileid,vcode,vtime')->order('vtime DESC')->where($verimap)->find();

//             if (!empty($verifyinfo)) {
//                 //判断验证信息是否过期
//                 if (NOW_TIME - $verifyinfo['vtime'] < $endtime) {
//                     $data['status'] = 2;
//                     $data['info'] = "密码仍在有效期内,请填写您收到的密码";
//                     $this->ajaxReturn($data);
//                 }
//             }
            //if($type && $type == "losepwd"){
            //忘记密码
            //$msg = C('SITECONFIG.SITE_NAME').'用户:'.$mobileinfo['username'].'找回密码操作,新密码为:'.$password.' 密码15分钟内有效';
            //}else{
            //注册、绑定手机
            //$msg = C('SITECONFIG.SITE_NAME').'手机用户注册:您的'.C('SITECONFIG.SITE_NAME').'账号为您的手机号,密码为:' . $password . '密码15分钟内有效,如您未注册,请勿理会,谢谢';
            //}
            $msg = '【' . C('SITECONFIG.SITE_NAME') . '】登录密码：' . $password . '，15分钟有效，注意保密，关注“' . C('SITECONFIG.SITE_NAME') . '”微信号，抢红包、免费看书！';
            //INSERT INTO wis_mobilevcode set vcode=\''.$md5password.'\',mobileid=\''.$mobileId.'\',vtime='.$M_time
            $arr = array(
                'vcode'    => $pwd,
                'mobileid' => $mobile,
                'vtime'    => NOW_TIME,
            );
            $vcodeModel = M('mobilevcode');
            $res = $vcodeModel->add($arr);
            if (!$res) {
                $output['message'] = "系统发生错误，请稍后再试";
                $this->ajaxReturn($output);
            }
            //TODO 测试代码
            if (C('APP_DEBUG')) {
                $output['status']  = 1;
                $output['message'] = $msg;
                $this->ajaxReturn($output);
            } else {
                //发送短信,成功返回OK，其他均失败
                $status = sendMobileMsg($mobile, $msg);
                if ($status != 'ok') {
                    //DELETE FROM wis_mobilevcode WHERE id={$insertid}
                    $delmap            = array("id" => $res);
                    $ret               = M('mobilevcode')->where($delmap)->delete();
                    $output['message'] = '发送短信时发生系统错误,请稍候再试';
                    $this->ajaxReturn($output);
                } else {
                    //设置短信缓存时间间隔
                    S($mobregname, 60, 60);
                    $output['status']  = 1;
                    $output['message'] = '您的登陆密码已发送到您的手机,请在收到后,在此输入即可完成注册';
                    $this->ajaxReturn($output);
                }
            }
        } else {
            send_http_status(400);
        }
    }

    /**
     * 手机注册保存账号密码
     *
     * @param int $mobileId 手机号码 post
     * @param string $password 密码 post
     * @param int $vecode 验证码 post
     *
     * @return string 注册成功/失败信息
     * */
    public function _saveRegister() {
        $output   = array("status" => 0, "message" => "", "url" => "");
        $sex_flag = I("param.sex_flag", "", "trim");
        //检测手机号码
        if (IS_POST) {
            $mobile = I('post.mobileId', 0, "intval");
            if (!isValidPhone($mobile)) {
                $output['message'] = "请输入正确的手机号码";
                $this->ajaxReturn($output);
            }
            $userModel  = new \Client\Model\UserModel();
            $illegalmob = $userModel->checkUserNameExist($mobile);
            if ($illegalmob) {
                $output['message'] = '您输入的手机号已注册' . C('SITECONFIG.SITE_NAME') . '账号,请输入其他未注册的手机号';
                $this->ajaxReturn($output);
            }
            //过期时间
            $endtime    = 60 * 15;
            $verimap    = array("mobileid" => $mobile);
            $verifyinfo = M('mobilevcode')->field('mobileid,vcode,vtime')->order('vtime DESC')->where($verimap)->find();
            if (empty($verifyinfo)) {
                $output['message'] = '系统错误，请重新输入手机号码获取密码';
                $this->ajaxReturn($output);
            } else {
                //判断验证信息是否过期
                if (NOW_TIME - $verifyinfo['vtime'] > $endtime) {
                    $output['message'] = '密码已过期,请重新获取';
                    $this->ajaxReturn($output);
                }
            }
            $password = I('post.password', 0, "trim");
            //$vecode = I('post.vcode', 0, 'trim');
            if (!$password) {
                $output['message'] = '请输入密码';
                $this->ajaxReturn($output);
            } else if (strlen($password) < 6) {
                $output['message'] = '输入密码过短';
                $this->ajaxReturn($output);
            } else {
                $pwd = $userModel->pwdEncrypt($password);
                if ($pwd != $verifyinfo['vcode']) {
                    $output['message'] = '密码错误！';
                    $this->ajaxReturn($output);
                }
            }
            /* 手机号、密码验证完成 */
            $email    = $mobile . '@yanqingkong.com';
            $emailres = $userModel->checkEmailExist($email);
            if ($emailres) {
                $output['message'] = '用户名已存在';
                $this->ajaxReturn($output);
            }
            $username = $mobile;
            //完成注册
            $rest     = $userModel->add($username, $password, $email, $mobile);
            if ($rest) {
                /* 返回值 */
                $userinfo          = M("user")->find($rest);
                //计算authcode
                $authcode          = $userModel->generate_authcode($userinfo);
                $data              = $userinfo;
                $data['status']    = 1;
                $data['usercode'] = $authcode;
                $data['groupname'] = C("USERGROUP")[$userinfo['groupid']]['title'];
                $data['avatar']    = getUserFaceUrl($userinfo['uid']);
                $data['isauthor']  = session('isauthor');
                if (in_array($sex_flag, array("sex_flag" => $sex_flag))) {
                    $data['url'] = url("User/login", array("sex_flag" => $sex_flag), 'do');
                } else {
                    $data['url'] = url("User/login", array(), 'do');
                }
                $this->ajaxReturn($data);
            } else {
                $output['message'] = '注册失败，请重试';
                $this->ajaxReturn($output);
            }
        } else {
            send_http_status(400);
        }
    }

    /**
     * 验证码验证
     *
     * @param int $imgcode 用户输入验证码 post
     * @param int $sesscode session中的验证码
     *
     * @return 验证失败/成功信息
     */
    public function _CheckImgCode() {
        $output  = array('status' => 0, 'message' => '', 'url' => '');
        $imgcode = I("post.sbm", 0, "intval");
        if (!$imgcode) {
            $output['message'] = '识别码为空';
            $this->ajaxReturn($output);
        }
        session('[start]');
        $sesscode = session("imgcode");
        if ((int) $sesscode == $imgcode) {
            $output['status']  = 1;
            $output['message'] = '验证成功';
            $this->ajaxReturn($output);
        } else {
            $output['message'] = '验证失败';
            $this->ajaxReturn($output);
        }
    }

    /**
     * 修改昵称
     *
     * @param int $uid 用户id session
     * @param string $nickname 新昵称 post
     *
     * @return 修改成功/失败信息
     */
    public function _changenickname() {
        $output = array("status" => 0, "message" => "", "url" => "");
        //检测登录
        $uid    = isLogin();
        if (!$uid) {
            $output['message'] = "请先登录";
            $this->ajaxReturn($output);
        }
        //获取新昵称
        $nickname = I("post.nickname", "", "trim");
        $nickname = trim(preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $nickname)); //过滤表情
        if (!$nickname) {
            $output['message'] = "昵称不能为空";
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        //判断是否修改过昵称
        $chgmap    = array("uid" => $uid);
        $changed   = $userModel->where($chgmap)->getField("nickallow");
        if ($changed) {
            $output['message'] = '昵称只能修改一次，您已修改过昵称！';
            $this->ajaxReturn($output);
        }
        //判断昵称是否重复
        $repeated = $userModel->checkNickNameExist($nickname);
        if ($repeated) {
            $output['message'] = '昵称已存在';
            $this->ajaxReturn($output);
        }
        //修改昵称
        $ret = $userModel->userSet($uid, $nickname);
        if ($ret) {
            //更新session昵称
            session("nickname", $nickname);
            $output['status']  = 1;
            $output['message'] = '修改成功';
            $output['url']     = url("User/personal", array(), 'do');
            $this->ajaxReturn($output);
        } else {
            $output['message'] = '修改失败，请重试';
            $this->ajaxReturn($output);
        }
    }

    /**
     * 充值记录
     *
     * @param int $uid 用户id session
     * @param int $totalnum 总的记录条数 get
     * @param int $pagenum 当前页码 get
     *
     * @return json
     */
    public function _paylogs() {
        $output = array("status" => 0, "message" => "", "url" => "");
        //判断登录
        $uid    = isLogin();
        if (!$uid) {
            $output['message'] = '请先登录';
            $this->ajaxReturn($output);
        }
        $pModel = D('Paylogs');
        $maxnum   = 10;
        $totalnum = I('get.totalnum', 0, 'intval');
        if (!$totalnum) {
            $talmap   = array("buyid" => $uid);
            $totalnum = $pModel->where($talmap)->count();
        }
        //$pagenum      = I('get.pagenum', 1, 'intval');
        $pagesize     = I('get.pagesize', 10, 'intval');
        $clientmethod = I('get.clientmethod', '', 'trim');
        $total_page   = ceil($totalnum / $maxnum);
        //分页数据
        $pageModel    = new \HS\Pager($totalnum, $pagesize);
        if ($clientmethod) {
            $pageModel->clientmethod = $clientmethod;
        }
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next', '>');
        $pageModel->setConfig('first', '|<');
        $pageModel->setConfig('last', '>|');
        $pagelist = $pageModel->show();
        $rowmap   = array("a.buyid" => $uid);
        $paylogs  = $pModel->alias("a")->
                join("__PAYACTIVITYLOGS__ AS b on a.payid = b.payid", 'left')->
                where($rowmap)->
                field('a.*, b.hongshumoney')->
                order("a.buytime desc")->limit($pageModel->firstRow, $pageModel->listRows)->select();
        if ($paylogs === false) {
            $output['message'] = "暂无记录";
            $this->ajaxReturn($output);
        }
        $today   = date('Ymd', NOW_TIME);
        foreach ($paylogs as &$row) {
            $row['addtime'] = date("Y-m-d H:i", $row['buytime']);
            if ($row['buytime']) {
                $addtime = date("Ymd", $row['buytime']);
                if ($addtime == $today) {
                    $row['today'] = 'yes';
                }
            }
            $row['payactivityegold'] = $row['hongshumoney'];
            //充值名称
            $row['payname']          = getPayConfig($row['paytype'], 'name');
        }
        if ($paylogs) {
            $output['list']        = $paylogs;
            $output['pagenum']     = $pageModel->nowPage;
            $output['nextpagenum'] = $pageModel->nowPage + 1;
            $output['totalpage']   = $total_page;
            $output['totalnum']    = $totalnum;
            $output['status']      = 1;
            $output['pagelist']    = $pagelist;
        } else {
            $output['status']   = 0;
            $output['totalnum'] = 0;
            $output['message']  = "暂无记录";
        }
        unset($pModel);
        $this->ajaxReturn($output);
    }
    /**
     * 喵阅读，充值记录
     * @param int $moneytype (=0喵币，=1喵豆,默认喵币) get
     * @param int $paytype(充值方式 =0全部 =1支付宝，=2微信,默认全部) get
     * @param int $pagenum get
     * @param int $pagesize get
     * @param int $totalnum get
     * 
     * @return
     *  gold充值获得的喵币
     *  time时间
     */
    public function _paylogs_myd(){
        $output = array('status'=>0,'message'=>'','url'=>'','list'=>array());
        $moneytype = I('get.moneytype',0,'intval');
        $paytype = I('get.paytype',0,'intval');
        $pagenum = I('get.pagenum',1,'intval');
        $pagesize = I('get.pagesize',10,'intval');
        $pagelistsize = I('get.pagelistsize',10,'intval');
        $totalnum = I('get.totalnum',0,'intval');
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $this->ajaxReturn($output);
        }
        $paylogsModel = new \Client\Model\PaylogsModel();
        if(!$totalnum){
            $map = array();
            $map['buyid'] = $uid;
            if($moneytype){
                $map['moneytype'] = $moneytype;
            }
            //充值方式
            if($paytype == 1){
                $map['paytype'] = array('IN',array('ALIPAY_SDK','ALIPAY_SDK','ALIPAY_WAP'));
            }elseif($paytype == 2){
                $map['paytype'] = array('IN',array('WECHATPAY','WEIXINPAY','WEIXINPAY_QRCODE'));
            }
            $totalnum = $paylogsModel->where($map)->count();
        }
        if(intval($totalnum) < 1){
            $output['message'] = '暂无记录';
            $this->ajaxReturn($output);
        }
        $pageModel = new \HS\Pager($totalnum, $pagesize);
        $pagelist = $pageModel->show();
        $where = array();
        $where['p.buyid'] = $uid;
        if($moneytype){
            $where['p.moneytype'] = $moneytype;
        }
        //充值方式
        if($paytype == 1){
            $where['p.paytype'] = array('IN',array('ALIPAY_SDK','ALIPAY_SDK','ALIPAY_WAP'));
        }elseif($paytype == 2){
            $where['p.paytype'] = array('IN',array('WECHATPAY','WEIXINPAY'));
        }
        $list = $paylogsModel->alias('p')->join('__PAYACTIVITYLOGS__ AS a ON a.payid = p.payid','LEFT')
                ->field('p.payid,p.paytype,p.buytime,p.egold,p.money,p.payflag,a.paymoney,a.hongshumoney')
                ->where($where)->order('p.buytime DESC')->limit($pageModel->firstRow,$pageModel->listRows)->select();
        if($list && is_array($list)){
            //格式化数据
            foreach($list as $key=>$val){
                $list[$key]['name'] = getPayConfig($val['paytype'],'name');
                $list[$key]['gold'] = $val['egold'];
                $list[$key]['time'] = date('Y/m/d H:i', $val['buytime']);
            }
            $pageListStart = (ceil($pagenum/$pagelistsize) - 1) * $pagelistsize + 1;
            $output['pageliststart'] = $pageListStart;
            $output['status'] = 1;
            $output['list'] = $list;
            $output['pagenum'] = $pagenum;
            $output['totalnum'] = $totalnum;
            $output['totalpage'] = $pageModel->totalPages;
        }else{
            $output['message'] = '暂无充值记录';
        }
        $this->ajaxReturn($output);
    }

    /**
     * 消费记录
     *
     * @param int $uid 用户id session
     * @param int $pagenum 当前页码
     * @param int $totalnum 总条数
     * @param int $total_page 总页数
     * @param int $year 年
     * @param int $month 月
     *
     * @return json
     */
    public function _salelogs() {
        $output = array("status" => 0, "message" => "", "url" => "", "pagelist" => '');
        $uid    = isLogin();
        if (!$uid) {
            $output['message'] = "请登录";
            $output['url']     = url("User/login", array("fu" => url("User/salelogs", array(), 'do')), 'do');
            $this->ajaxReturn($output);
        }

        $pagenum      = I("get.pagenum", 1, 'intval');
        $total_page   = I("get.totalpage", 0, 'intval');
        $totalnum     = I("get.totalnum", 0, 'intval');
        $pagesize     = I('get.pagesize', 10, 'intval');
        $clientmethod = I('get.clientmethod', '', 'trim');
        $year         = I("param.year", '', 'trim');
        $month        = I("param.month", '', 'trim');
        if ($year && $month) {
            if ($month < 10) {
                $month = "0" . $month;
            }
            $yearsub = substr($year, 2, 2);
            $days    = $yearsub . $month;
            if (date("ym", NOW_TIME) < $days) {
                $output['message'] = '参数错误';
                $this->ajaxReturn($output);
            }
            $salelogtables = get_salelogsTablename($days);
        } else {
            $year          = date("Y", NOW_TIME);
            $month         = date("m", NOW_TIME);
            $salelogtables = get_salelogsTablename(date("ym", NOW_TIME));
        }
        $tabName = substr($salelogtables, 4);
        if (!$totalnum) {
            $map      = array("uid" => $uid);
            if(CLIENT_NAME == 'myd'){
                //喵阅读要取当月总消费
                $tmp = M($tabName)->field('count(*) AS totalnum,sum(saleprice) AS totalmoney,moneytype')
                        ->where($map)->group('moneytype')->select();
                if($tmp && is_array($tmp)){
                    $totalnum = 0;
                    foreach($tmp as $val){
                        $totalnum += $val['totalnum'];
                        if($val['moneytype'] == 1){
                            //银币
                            $output['totalemoney'] = $val['totalmoney'];
                        }elseif($val['moneytype'] == 2){
                            //金币
                            $output['totalmoney'] = $val['totalmoney']; 
                        }
                    }
                    $output['totalnum'] = $totalnum;
                }
            }else{
                $totalnum = M($tabName)->where($map)->count();
            }
        }
        if (!$total_page) {
            $total_page = ceil($totalnum / $pagesize);
        }
        $pageModel = new \HS\Pager($totalnum, $pagesize);
        if ($clientmethod) {
            $pageModel->clientmethod = $clientmethod;
        }
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next', '>');
        $pageModel->setConfig('first', '|<');
        $pageModel->setConfig('last', '>|');
        $pagelist = $pageModel->show();

        $where = array(
            'log.uid' => $uid,
        );
//         $saleModel = M($tabName);
        $rows  = M($tabName)->alias('log')->join('__BOOK__ AS book on log.bid=book.bid', 'left')->field('log.*,book.catename')
                ->where($where)->order('log.saletime DESC')->limit($pageModel->firstRow, $pageModel->listRows)->select();
//         $besesql = "SELECT log.*,wis_book.catename FROM {$salelogtables} AS log LEFT JOIN (wis_book) ON (wis_book.bid=log.bid) where log.uid={$uid}";
//         $newsql = $besesql . " ORDER BY log.saletime DESC limit " . $startnum . ", " . $maxnum;
//         $rows = M()->query($newsql);
        if (!$rows) {
            $output['totalnum'] = 0;
            $output['message']  = "暂无记录";
            $this->ajaxReturn($output);
        } else {
            $start = $pageModel->firstRow + 1;
            foreach ($rows as $row) {
                if ($row['moneytype'] == 2) {
                    $row['moneytypestr'] = C('SITECONFIG.MONEY_NAME');
                } else {
                    $row['moneytypestr'] = C('SITECONFIG.EMONEY_NAME');
                }
                $row['time'] = date("Y-m-d H:i", $row['saletime']);
                $row['ordernum'] = $start;
                $start ++;
                $salelogs[]  = $row;
            }
            //起始页面
            if(CLIENT_NAME == 'myd'){
                $pageListSize = I('get.pagelistsize',10,'intval');
                $pageliststart = (ceil($pagenum/$pageListSize) -1) * $pageListSize + 1;
                $output['pageliststart'] = $pageliststart;
            }
            $output['status']      = 1;
            $output['pagenum']     = $pageModel->nowPage;
            $output['nextpagenum'] = $pageModel->nowPage + 1;
            $output['totalpage']   = $total_page;
            $output['totalnum']    = $totalnum;
            $output['list']        = $salelogs;
            $output['pagelist']    = $pagelist;
            $this->ajaxReturn($output);
        }
    }

    /**
     * 获取书架列表数据
     * @param int $totalnum 总记录条数
     * @param int $pagenum 当前页码
     * @param int $total_page 总页码
     * @return array 书架的书籍信息
     */
    public function _getshelflist() {
        $output = array("status" => 0, "message" => "", "url" => "");
        $uid    = isLogin();
        if (!$uid) {
            $output['message'] = "请先登录";
            $output['url']     = url("User/login", array(), 'do');
            $this->ajaxReturn($output);
        }
        $pagenum      = I('get.pagenum', 1, 'intval');
        $pagesize     = I('get.pagesize', 10, 'intval');
        $totalnum     = I("get.totalnum", 0, 'intval');
        $clientmethod = I('get.clientmethod', '', 'trim');
        if (!$totalnum) {
            $talmap   = array("uid" => $uid);
            $totalnum = M("fav")->where($talmap)->count();
        }
        if (!$totalnum) {
            $output['message'] = "暂无记录";
            $this->ajaxReturn($output);
        }
        $total_page = I("get.totalpage", 0, "intval");
        if (!$total_page) {
            $total_page = ceil($totalnum / $pagesize);
        }
        $pageModel = new \HS\Pager($totalnum, $pagesize);
        if ($clientmethod) {
            $pageModel->clientmethod = $clientmethod;
        }
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next', '>');
        $pageModel->setConfig('first', '|<');
        $pageModel->setConfig('last', '>|');
        $pagelist = $pageModel->show();
        //获取数据库书架favbooks
        $favmap   = array("f.uid" => $uid);
        $favBooks = M("fav")
                ->alias('f')
                ->field('f.fid,f.bid,f.bookmark,f.uid,b.bid,b.catename,b.last_updatetime,last_vipupdatetime,b.last_vipupdatechpid,b.last_vipupdatechptitle,b.last_updatechptitle,b.last_updatechpid,b.classid,b.classid2,b.tags,b.author')
                ->join('__BOOK__ AS b on f.bid=b.bid', 'LEFT')
                ->where($favmap)->order('f.fid DESC')->limit($pageModel->firstRow, $pageModel->listRows)->select();
        if (!$favBooks) {
            $output['message'] = "暂无记录";
            $this->ajaxReturn($output);
        }
        
        foreach ((array) $favBooks as $key => $row) {
            //拆分书签
            $mark = explode("\t", $row['bookmark']);
            if (count($mark) <= 1) {
                $mark = explode('\t', $row['bookmark']);
            }
            if (count($mark) < 1) {
                unset($favBooks[$key]);
                continue;
            }
            /* 最后阅读章节：阅读记录>书架 */
            $markchpid   = intval($mark[1]); //书架中保存的章节id
            $cookiebook  = getBookCookieFav($row['bid']); //阅读记录
            $cookiechpid = 0;
            if ($cookiebook) {
                $cookiechpid = intval($cookiebook['chapterid']);
            }
            $chpid = $cookiechpid ? $cookiechpid : $markchpid;
            if ($chpid) {
                $row['lastchapterid'] = $chpid;
            } else {
                unset($favBooks[$key]);
                continue;
            }
            $bookModel = new \Client\Model\BookModel();
//             $chapters  = $bookModel->getChapter($row['bid']);
            $chapters = $bookModel->getChplistByBid($row['bid']);
            if (!$chapters || !$chapters['list']) {
                unset($favBooks[$key]);
                continue;
            }
            $row['lastupdatechpid']   = $row['last_vipupdatechpid'] ? $row['last_vipupdatechpid'] : $row['last_updatechpid'];
            $totalChpNum              = count($chapters['list']); //总章节数
            $last_update_chapter_info = array(); //最后更新章节信息
            $last_read_chapter_info   = array(); //最后阅读章节信息

            foreach ($chapters['list'] as $vo) {
                //取到最后一章和当前章节则跳出循环
                if ($last_update_chapter_info && $last_read_chapter_info) {
                    break;
                }
                if ($vo['chapterid'] == $row['lastupdatechpid']) {
                    $last_update_chapter_info = $vo;
                }
                if ($vo['chapterid'] == $chpid) {
                    $last_read_chapter_info = $vo;
                }
            }
            if (!$last_update_chapter_info) {
                $last_update_chapter_info = array_pop($chapters['list']);
            }
            $row['imgurl'] = getBookfacePath($row['bid'], 'middle');
            //最后更新时间和最后更新章节id
            if ($row['last_vipupdatechpid']) {
                $updatetime = $row['last_vipupdatetime'];
            } else {
                $updatetime = $row['last_updatetime'];
            }
            //最后更新的时间到现在的时间间隔
            $tmptime                = NOW_TIME - $updatetime;
            $row['last_updatetime'] = $updatetime;

            //最后更新的章节号
            $row['last_readchpnum'] = $last_read_chapter_info['chporder'];
            if ($row['last_readchpnum'] <= 0) {
                $row['last_readchpnum'] = 1;
            }
            $marktime = $mark[2];
            if ($updatetime > $mark[2]) {
                $row['marknew'] = 1;
            }
            //分类名称
            $row['category']    = C('CATEGORY')[$row['classid']]['subclass'][$row['classid2']]['smalltitle'];
            //总章节数
            $row['totalChpNum'] = $totalChpNum;
            //tags
            $tags               = explode(' ', $row['tags']);
            if (is_array($tags) && $tags[0]) {
                $row['tags'] = $tags;
            } else {
                $row['tags'] = array();
            }
            //作者名
            $row['authorname'] = $row['author'];
            //最后阅读章节
            if ($last_read_chapter_info) {
                $row['isread']          = 1;
                $row['last_readchpid']  = $last_read_chapter_info['chapterid'];
                $row['isvip']           = intval($last_read_chapter_info['isvip']);
                $row['last_readchpnum'] = $last_read_chapter_info['chporder'];
            } else {
                $row['isread']          = 0;
                $row['last_readchpid']  = 0;
                $row['isvip']           = 0;
                $row['last_readchpnum'] = 1;
            }

            $booklist[] = $row;
        }

        //判断是否得到数据
        if ($booklist && is_array($booklist)) {
            $output['status']      = 1;
            $output['pagelist']    = $pagelist;
            $output['list']        = $booklist;
            $output['pagenum']     = $pageModel->nowPage;
            $output['nextpagenum'] = $pageModel->nowPage + 1;
            $output['totalpage']   = $total_page;
            $output['totalnum']    = $totalnum;
        } else {
            $output['status']   = 0;
            $output['totalnum'] = 0;
            $output['message']  = '暂无数据';
        }
        $this->ajaxReturn($output);
    }

    /**
     * 个人中心的自动订阅显示页
     *
     * @param int $uid 用户id（session获取）
     * @param int $pagenum 当前页码
     * @param int $totalnum 自动订阅书籍总数
     *
     * @return array
     */
    public function _autodingyue() {
        $output = array("status" => 0, "message" => "", "url" => "");
        //读取用户登录的id
        $uid    = isLogin();
        if (!$uid) {
            $output['message'] = "请先登录";
            $output['url']     = url("User/login", array(), 'do');
            $this->ajaxReturn($output);
        }
        $pagenum      = I('get.pagenum', 1, 'intval');
        $pagesize     = I('get.pagesize', 6, 'intval');
        $clientmethod = I('get.clientmethod', '', 'trim');
        $totalnum     = I('get.totalnum', '', 'intval');

        $where = array(
            'auto.uid' => $uid,
            //'auto.dtype'=>3,
        );
        if (!$totalnum) {
            $talmap   = array("uid" => $uid);
            $totalnum = M('autodingyueset')->where($talmap)->count();
        }
        $total_page = I('get.totalpage', 0, 'intval');
        if (!$total_page) {
            $total_page = ceil($totalnum / $pagesize);
        }

        $pageModel = new \HS\Pager($totalnum, $pagesize);
        if ($clientmethod) {
            $pageModel->clientmethod = $clientmethod;
        }
        $pageModel->setConfig("prev", "<");
        $pageModel->setConfig("next", ">");
        $pageModel->setConfig("first", "|<");
        $pageModel->setConfig("last", ">|");
        $pagelist = $pageModel->show();
        $list     = M('autodingyueset')->alias("auto")->join("__BOOK__ as book on auto.bid=book.bid")->
                where($where)->order("auto.dtime DESC")->limit($pageModel->firstRow, $pageModel->listRows)->select();

//         $basesql = "SELECT a.bid,b.catename FROM wis_autodingyueset as a left join wis_book as b on a.bid=b.bid WHERE a.uid=" . $uid;
//         $startnum = ($pagenum - 1) * $maxnum;
//         $newsql = $basesql . " ORDER BY  dtime DESC limit " . $startnum . "," . $maxnum;
//         $list = M()->query($newsql);
        $output['pagenum']     = $pageModel->nowPage;
        $output['nextpagenum'] = $pageModel->nowPage + 1;
        $output['totalpage']   = $total_page;
        $output['totalnum']    = $totalnum;
        if (!$list) {
            $output['pagelist'] = '';
            $output['message']  = "暂无记录";
        } else {
            $output['status']   = 1;
            $output['list']     = $list;
            $output['pagelist'] = $pagelist;
        }
        $this->ajaxReturn($output);
    }

    /**
     * 用户中心：保存订阅修改，暂未使用,Ajax中有对应方法
     * @param int $uid 用户id session获取
     * @param int $bid 书籍id post传递
     * @return string 修改成功/失败信息
     */
    public function _saveautodingyue() {
        $output = array("status" => 0, "message" => "", "url" => "", "type" => '');
        //读取用户登录的id
        $uid    = isLogin();
        if (!$uid) {
            $output['message'] = "请先登录";
            $output['url']     = url("User/login", array(), 'do');
            $this->ajaxReturn($output);
        }
        //要修改的书籍bid
        $bidstr = I('post.bid', '', 'trim');
        if ($bidstr == "") {
            $output['message'] = "参数错误";
            $this->ajaxReturn($output);
        }
        $type = I('post.type', '', 'trim');
        if (!$type) {
            $type = 'del';
        }
        if (!in_array($type, array('del', 'save'))) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $output['type'] = $type;
        //删除操作
        if ($type == 'del') {
            //执行取消自动订阅操作
            $where  = array(
                'bid' => array('IN',$bidstr),
                'uid' => $uid,
            );
            $result = M('autodingyueset')->where($where)->delete();
            //$result = M()->execute("DELETE FROM wis_autodingyueset WHERE bid in({$bidstr}) and uid={$uid}");
            if ($result) {
                //写日志
                $data              = array(
                    'fromid'      => $uid,
                    'logtime'     => session('lastlogin'),
                    'fromname'    => session('username'),
                    'chglog'      => CLIENT_NAME.'用户中心取消自动订阅小说,书号:' . $bidstr,
                    'runprograme' => $_SERVER['PHP_SELF'],
                );
//                 $sql_insert = "INSERT INTO wis_systemlogs(logid,fromid,logtime,fromname,chglog,runprograme) VALUES(null,$uid,'" . session('lastlogin') . "','" . session('username') . "','" . 'html5用户中心取消自动订阅小说,书号:' . $bidstr . "','" . $_SERVER['PHP_SELF'] . "')";
//                 $r = M('systemlogs')->execute($sql_insert);
                M('systemlogs')->add($data);
                $output['status']  = 1;
                $output['message'] = '设置成功';
            } else {
                $output['message'] = "出错了，请稍等再试";
            }
        } elseif ($type == 'save') {
            $data     = array(
                'uid'      => $uid,
                'bid'      => $bidstr,
                'dtype'    => 3,
                'dtime'    => NOW_TIME,
                'fromSite' => C('CLIENT.' . CLIENT_NAME . '.fromsiteid'),
            );
            $model = M('Autodingyueset');
            $insertid = $model->add($data);
            if ($insertid) {
                $output['status']  = 1;
                $output['message'] = '设置成功';
            } else {
                $output['message'] = '网络错误，请重试';
            }
        }
        $this->ajaxReturn($output);
    }

    /**
     * 绑定手机
     *
     * @param int $mobileId 手机号码 post
     */
    public function _savemobile() {
        $output   = array("status" => 0, "message" => "", "url" => "");
        $sex_flag = I("param.sex_flag", "", "trim");
        //检测登录
        $uid      = isLogin();
        if (!$uid) {
            $output['message'] = "请先登录";
            if (in_array($sex_flag, array("nan", "nv"))) {
                $output['url'] = url("User/login", array("sex_flag" => $sex_flag), 'do');
            } else {
                $output['url'] = url("User/login", array(), 'do');
            }
            $this->ajaxReturn($output);
        }
        //获取手机号
        $mobileId = I("post.mobileId", 0, "intval");
        //图片验证码
        $imgcode  = I("post.sbm", 0, "intval");
        if (!$imgcode) {
            $output['message'] = '请输入验证码';
            $this->ajaxReturn($output);
        }
        if (intval(session('imgcode')) != $imgcode) {
            $output['message'] = '验证码错误';
            $this->ajaxReturn($output);
        }
        //短信验证码
        $vericode = I("post.password", 0, "intval");
        if (!$vericode) {
            $output['message'] = '验证码不能为空';
            $this->ajaxReturn($output);
        }
        $endtime  = 60 * 15;
        //是否发送过验证码
        $verimap  = array("mobileid" => $mobileId);
        $veriinfo = M("mobilevcode")->field("mobileid,vcode,vtime")->where($verimap)->order(" vtime DESC")->find();
        if (!$veriinfo) {
            $output['message'] = '请输入手机号，获取验证码';
            $this->ajaxReturn($output);
        }
        //验证码是否过期
        if ((NOW_TIME - $veriinfo['vtime']) > $endtime) {
            $output['message'] = '验证码过期，请重新获取';
            $this->ajaxReturn($output);
        }
        //验证码是否正确
        $userModel = new \Client\Model\UserModel();
        $md5pwd    = $userModel->pwdEncrypt($vericode);
        if ($md5pwd != $veriinfo['vcode']) {
            $output['message'] = '验证码错误';
            $this->ajaxReturn($output);
        }
        //验证手机号码
        if (!$mobileId || $mobileId <= 0) {
            $output['message'] = '手机号码错误';
            $this->ajaxReturn($output);
        }
        $isgoodmobile = isValidPhone($mobileId);
        if (!$isgoodmobile) {
            $output['message'] = '请输入正确的手机号码';
            $this->ajaxReturn($output);
        }
        //是否已经绑定手机
        $readObj  = M("read_user");
        $readmap  = array("uid" => $uid);
        $readinfo = $readObj->where($readmap)->find();
        if ($readinfo && $readinfo['mobile']) {
            $output['message'] = '您的' . C('SITECONFIG.SITE_NAME') . '账号已绑定手机,如需解绑或换号请联系客服';
            $this->ajaxReturn($output);
        }
        //手机号是否已经被绑定
        $bindmap      = array("mobile" => $mobileId);
        $isbindmobile = $readObj->where($bindmap)->count();
        if ($isbindmobile > 0) {
            $output['message'] = '该手机已被使用,请换个手机号';
            $this->ajaxReturn($output);
        }
        if ($veriinfo) {
            //更新用户表
            $ret = $userModel->userSet($uid, '', $mobileId);
            if ($ret) {
                //获得当前ip
                $ip            = get_client_ip();
                $mobregname    = "mobreg_" . $ip;
                //同一IP发送的限制时间
                $IP_place_time = 60 * 60;
                S(C('rdconfig'));
                S($mobregname, 1, $IP_place_time);
                /*2017.1.16取消手机绑定/注册送银币
                if ($mobileId) {
                    if (CLIENT_NAME === 'android') {
                        //第一次登录送银币
                        $firmap         = array("uid" => $readinfo['uid']);
                        $firstLoginInfo = M("android_bounry")->where($firmap)->getField("uid");
                        if (!$firstLoginInfo) {
                            $bounry_num = 100;
                            //INSERT INTO wis_android_bounry set uid='.$weuserinfo['uid'].',addtime='.$M_time.',egold='.$bounry_num)
                            $bounryData = array(
                                "uid"     => $readinfo["uid"],
                                "addtime" => NOW_TIME,
                                "egold"   => $bounry_num,
                            );
                            $bounryid   = M("android_bounry")->add($bounryData);
                            if ($bounryid) {
                                $addEgoldId = $userModel->addEgold($readinfo, $bounry_num);
                                if ($addEgoldId) {
                                    ;
                                } else {
                                    //失败则写日志,wis_systemlogs
                                    $sysData = array(
                                        'fromid'      => 0,
                                        'fromname'    => '安卓初次登录送银币',
                                        'toid'        => $readinfo['uid'],
                                        'toname'      => $readinfo['username'],
                                        'chglog'      => '安卓初次登录送银币' . $bounry_num . '时发生错误，赠送失败',
                                        'runprograme' => $_SERVER['PHP_SELF'],
                                    );
                                    $sysid   = M("systemlogs")->add($sysData);
                                }
                            }
                        }
                    } else if (CLIENT_NAME === 'ios') {
                        //第一次登录送银币
                        $iosmap         = array("uid" => $readinfo['uid']);
                        $firstLoginInfo = M("ios_bounry")->where($iosmap)->getField("uid");
                        if (!$firstLoginInfo) {
                            $bounry_num = 100;
                            //INSERT INTO wis_android_bounry set uid='.$weuserinfo['uid'].',addtime='.$M_time.',egold='.$bounry_num)
                            $bounryData = array(
                                "uid"     => $readinfo["uid"],
                                "addtime" => NOW_TIME,
                                "egold"   => $bounry_num,
                            );
                            $bounryid   = M("ios_bounry")->add($bounryData);
                            if ($bounryid) {
                                $addEgoldId = $userModel->addEgold($readinfo, $bounry_num);
                                if ($addEgoldId) {
                                    ;
                                } else {
                                    //失败则写日志,wis_systemlogs
                                    $sysData = array(
                                        'fromid'      => 0,
                                        'fromname'    => 'IOS版已有绑定手机用户初次登陆赠送银币',
                                        'toid'        => $readinfo['uid'],
                                        'toname'      => $readinfo['username'],
                                        'chglog'      => 'IOS版已有绑定手机用户初次登陆赠送银币' . $bounry_num . '时发生错误，赠送失败',
                                        'runprograme' => $_SERVER['PHP_SELF'],
                                    );
                                    $sysid   = M("systemlogs")->add($sysData);
                                }
                            }
                        }
                    }
                    $output['status']  = 1;
                    $output['message'] = '绑定成功';
                    $output['url']     = url("User/personal", array(), 'do');
                    $this->ajaxReturn($output);
                } else {
                */
                    $output['status']  = 1;
                    $output['message'] = '绑定成功';
                    $output['url']     = url("User/personal", array(), 'do');
                    $this->ajaxReturn($output);
                //}
            } else {
                $output['message'] = '绑定失败，请稍后重试';
                $this->ajaxReturn($output);
            }
        } else {
            $output['message'] = '您输入的手机号已经绑定，请更换新的号码';
            $this->ajaxReturn($output);
        }
    }

    /**
     * 修改密码
     *
     * @param string $oldpwd 旧密码 post
     * @param string $newpwd 旧密码 post
     *
     * @return 成功/失败的信息
     */
    public function _savepassword() {
        $output   = array("status" => 0, "message" => "", "url" => "");
        $sex_flag = I("param.sex_flag", "", "trim");
        //检测登录
        $uid      = isLogin();
        if (!$uid) {
            $output['message'] = "请先登录";
            if (in_array($sex_flag, array("nan", "nv"))) {
                $output['url'] = url("User/login", array("sex_flag" => $sex_flag), 'do');
            } else {
                $output['url'] = url("User/login", array(), 'do');
            }
            $this->ajaxReturn($output);
        }
        //获取参数
        $oldpwd = I("post.oldpwd", 0, "trim");
        if (!$oldpwd) {
            $output['message'] = '原密码不能为空';
            $this->ajaxReturn($output);
        }
        $newpwd = I("post.newpwd", 0, "trim");
        if (!$newpwd) {
            $output['message'] = '新密码不能为空';
            $this->ajaxReturn($output);
        }
        //验证旧密码
        if ($oldpwd == $newpwd) {
            $output['message'] = '新密码与原密码不能相同';
            $this->ajaxReturn($output);
        }
        $usemap   = array("uid" => $uid);
        $userinfo = M("user")->where($usemap)->find();
        if (!$userinfo) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        $md5oldpwd = $userModel->pwdEncrypt($oldpwd);
        if ($md5oldpwd == $userinfo['password']) {
            //更新密码
            $isupdate = $userModel->setPassword($uid, $newpwd);
            if ($isupdate) {
                $userModel->doLogout();
                $output['status']  = 1;
                $output['message'] = '修改成功,请牢记新密码';
                if (in_array($sex_flag, array("nan", "nv"))) {
                    $output['url'] = url("User/login", array("sex_flag" => $sex_flag), 'do');
                } else {
                    $output['url'] = url("User/login", array(), 'do');
                }
            } else {
                $output['message'] = '修改失败，请稍后重试';
            }
            $this->ajaxReturn($output);
        } else {
            $output['message'] = '原密码错误';
            $this->ajaxReturn($output);
        }
    }

    /**
     * 忘记密码登录
     */
    public function _losepwdlogin() {
        $output    = array("status" => 0, "message" => "", "url" => "");
        $mobileId  = I("post.mobileId", 0, "intval");
        $pwd       = I("post.password", 0, "intval");
        $imgcode   = I("post.sbm", 0, "intval");
        $userModel = new \Client\Model\UserModel();
        //图片验证码
        if (!$imgcode) {
            $output['message'] = '请输入验证码';
            $this->ajaxReturn($output);
        }
        if (intval(session('imgcode')) != $imgcode) {
            $output['message'] = '验证码错误';
            $this->ajaxReturn($output);
        }
        //验证密码
        if (!$pwd) {
            $output['message'] = '验证码不能为空';
            $this->ajaxReturn($output);
        }
        //验证手机
        if (!isValidPhone($mobileId) || !$mobileId) {
            $output['message'] = '请输入正确的手机号码';
            $this->ajaxReturn($output);
        }
        $mobileinfo = $userModel->getUserByPhone($mobileId);
        if (!$mobileinfo) {
            $output['message'] = '您输入的手机号未绑定' . C('SITECONFIG.SITE_NAME') . '账号,请输入其他已绑定的手机号';
            $this->ajaxReturn($output);
        }
        $endtime  = 60 * 15;
        //是否发送过验证码
        $verimap  = array("mobileid" => $mobileId);
        $veriinfo = M("mobilevcode")->field("mobileid,vcode,vtime")->where($verimap)->order(" vtime DESC")->find();
        if (!$veriinfo) {
            $output['message'] = '请输入手机号，获取验证码';
            $this->ajaxReturn($output);
        }
        //验证码是否过期
        if ((NOW_TIME - $veriinfo['vtime']) > $endtime) {
            $output['message'] = '验证码已过期，请重新获取';
            $this->ajaxReturn($output);
        }
        $md5pwd = $userModel->pwdEncrypt($pwd);
        if ($md5pwd != $veriinfo['vcode']) {
            $output['message'] = '验证码错误';
            $this->ajaxReturn($output);
        }
        //更新密码
        $updatepwd = $userModel->setPassword($mobileinfo['uid'], $pwd);
        if ($updatepwd) {
            //登录
            $islogin           = $userModel->login($mobileinfo['uid'], 365 * 24 * 3600);
            /* 返回值 */
            $usermap           = array("uid" => $mobileinfo["uid"]);
            $userinfo          = M("user")->where($usermap)->find();
            //计算authcode
            $authcode          = $userModel->generate_authcode($userinfo);
            $data              = array();
            $data['status']    = 1;
            $data['uid']       = $userinfo['uid'];
            $data['username']  = $userinfo['username'];
            $data['nickname']  = $userinfo['nickname'];
            $data['usercode']  = $authcode;
            $data['viplevel']  = $userinfo['viplevel'];
            $data['groupid']   = $userinfo['groupid'];
            $data['groupname'] = C("USERGROUP")[$userinfo['groupid']]['title'];
            $data['money']     = $userinfo['money'];
            $data['avatar']    = getUserFaceUrl($userinfo['uid']);
            $data['isauthor']  = session('isauthor');
            if (strstr($this->M_forward, url("User/login")) || strstr($this->M_forward, url("User/register"))) {
                $this->M_forward = '/';
            }
            $data['url'] = removeXSS($this->M_forward);
            $this->ajaxReturn($data);
        } else {
            $output['message'] = '验证失败，请稍后重试';
            $this->ajaxReturn($output);
        }
    }

    /**
     * 抽奖
     *
     */
    public function _lingjiang() {
        $output = array("status" => 0, "message" => "", "url" => "");
        if (CLIENT_NAME !== 'ios' && CLIENT_NAME !== 'android') {
            $output['message'] = '参数错误，请重试！';
            $output['url']     = $this->M_forward;
            $this->ajaxReturn($output);
            exit;
        }
        $tablename = CLIENT_NAME . '_qiandao';
        //登录
        $uid       = isLogin();
        if (!$uid) {
            $output['message'] = "请先登录";
            $output['url']     = url("User/login", array("fu" => url("User/lingjiang", array(), 'do')), 'do');
            $this->ajaxReturn($output);
        }
        $thisday = date("ymd");
        $uuid    = I('post.uuid', '', 'trim'); //$this->deviceInfo['UUID'];
        if (!$uuid) {
            $output['message'] = '抱歉,服务器繁忙,请稍后再来!';
            $this->ajaxReturn($output);
        }
        $qid = I("param.id", 0, "intval");
        if (!$qid) {
            $output['message'] = '参数错误，请重试';
            $this->ajaxReturn($output);
        }
        //奖项数组设置
        $lj_set_ary = array(0 => 8, 1 => 11, 2 => 16, 3 => 18, 4 => 21, 5 => 51);
        //奖励金额
        $idx        = I("param.idx", 0, "intval");
        //if(!$idx){
        //    $output['message'] = '参数错误.';
        //    $this->ajaxReturn($output);
        //}
        //$old_egold = $lj_set_ary[$idx];
        $jl_set_idx = $this->get_lj_idx();
        $give_egold = isset($lj_set_ary[$jl_set_idx]) ? $lj_set_ary[$jl_set_idx] : 0;
        if (!$give_egold) {
            $output['message'] = '未知错误，请重试';
            $this->ajaxReturn($output);
        }
        $where   = array(
            "uuid"        => $uuid,
            "qiandao_day" => $thisday,
            "id"          => $qid,
        );
        //12.28安卓增加uid
        if(CLIENT_NAME == 'android'){
            $where = array(
                'qiandao_day' => $thisday,
                array(
                    'uuid' => $uuid,
                    'uid' => $uid,
                    '_logic' => 'or',
                ),
            );
        }
        $lj_data = M($tablename)->where($where)->find();
        if (!$lj_data) {
            $output['message'] = '抱歉,服务器繁忙,请稍后再来';
            $this->ajaxReturn($output);
        }
        if ($lj_data['egold'] > 0) {
            $output['message'] = '您已领取，请明天再来';
            $this->ajaxReturn($output);
        }

        //加锁
        S(C("rdconfig"));
        $lockkey   = $tablename . '_' . $qid;
        $lockModel = new \HS\CacheLock(md5($lockkey));
        if (!$lockModel->lock()) {
            $output['status']  = -1;
            $output['message'] = '网络错误，请等待！';
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        $usermap   = array("uid" => $uid);
        $userinfo  = M('user')->where($usermap)->find();
        if (!$userinfo) {
            $lockModel->unlock();
            $output['message'] = '未知错误';
            $this->ajaxReturn($output);
        }
        //加egold
        $ret = $userModel->addEgold($userinfo, $give_egold);
        if ($ret) {
            $savedata  = array(
                "egold"        => $give_egold,
                "linjiang_day" => $thisday,
                "uid"          => $userinfo['uid'],
            );
            $tmpmap    = array("id" => $qid);
            $isupdated = M($tablename)->where($tmpmap)->save($savedata);
            $lockModel->unlock();
            //更新失败则写日志
            if ($isupdated) {
                $result     = $lj_set_ary;
                unset($result[$jl_set_idx]);
                $tmp        = shuffle($result);
                $lj_set_ary = array();
                $i          = 0;
                foreach ($result as $v => $k) {
                    if ($i == $idx) {
                        $lj_set_ary[] = $give_egold;
                    }
                    $lj_set_ary[] = $k;
                    $i++;
                }
                if ($i == $idx) {
                    $lj_set_ary[] = $give_egold;
                }

                $output['status']     = 1;
                $output['lj_set_ary'] = $lj_set_ary;
                $output['lj_idx']     = $idx;
                $output['lj_egold']   = $give_egold;
                $output['lj_jl_idx']  = $jl_set_idx;
                $output['message']    = '恭喜您，获得' . $give_egold . '个' . C('SITECONFIG.EMONEY_NAME');
                $this->ajaxReturn($output);
            } else {
                $sysdata           = array(
                    'fromid'      => 0,
                    'fromname'    => strtoupper(CLIENT_NAME) . '签到赠送银币',
                    'toid'        => $userinfo['uid'],
                    'toname'      => $userinfo['username'],
                    'chglog'      => '签到银币:' . $give_egold . '时发生错误,时间:' . $thisday,
                    'runprograme' => $_SERVER['PHP_SELF']
                );
                $sysid             = M("systemlogs")->add($sysdata);
                $output['message'] = '未知错误';
                $this->ajaxReturn($output);
            }
        } else {
            $lockModel->unlock();
            $output['message'] = '未知错误';
            $this->ajaxReturn($output);
        }
    }

    /**
     * 抽奖,获得奖项数组的idx
     */
    protected function get_lj_idx() {
        //中奖几率
        $lj_jl_set_ary = array(0 => 30, 1 => 50, 2 => 70, 3 => 90, 4 => 95, 5 => 100);
        //几率
        $lj_jl         = mt_rand(1, 100);
        $find          = false;
        //echo $lj_jl.'--<br/>';
        foreach ($lj_jl_set_ary as $lj_jl_idx => $lj_jl_set) {
            if ($lj_jl <= $lj_jl_set) {
                $find = true;
                break;
            }
        }
        if ($find) {
            return $lj_jl_idx;
        } else {
            return 0;
        }
    }

    /**
     * 收藏书架
     *
     * @param int $bid 书籍id get
     * @param int $chpid 章节id get
     *
     * @return string
     *      needlogin字段用来告知客户端是否需要登录(=1需要登录，=0不需要登录)--元气萌客户端开始启用
     */
    public function _insertfav() {
        $output = array("status" => 0, "message" => "", "url" => "", "neelogin"=>0);
        //书id
        $bid    = I('get.bid', 0, "intval");
        if (!$bid) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        //章节id
        $chpid = I('get.chpid', 0, "intval");
        //uid
        $uid   = isLogin();
        if (!$uid) {
            $output['needlogin'] = 1;
            $output['message'] = '请先登录';
            $output['url']     = url("User/login", array(), 'do');
            $this->ajaxReturn($output);
        }
        $data      = '';
        //检查是否已经收藏该书
        $bookModel = new \Client\Model\BookModel();
        $favbook   = $bookModel->checkFav($uid, $bid);
        if ($favbook) {
            $output['message'] = '已收藏,请阅读';
            $this->ajaxReturn($output);
        }
        //章节id,如果存在则获取章节信息
        if ($chpid) {
            $chpinfo = $bookModel->getChapterByCid($bid, $chpid);
            if ($chpinfo === false) {
                $output['message'] = '没有找到要收藏的章节';
                $this->ajaxReturn($output);
            }
            //从数据库查询书籍信息
            $bookinfo                        = $bookModel->getBook($bid, 1);
            $bookinfo['last_updatechptitle'] = $chpinfo['title'];
            $bookinfo['last_updatechpid']    = $chpid;
            $bookinfo['last_updatetime']     = $chpinfo['publishtime'];
            $bid                             = $chpinfo['bid'];
        } elseif ($bid) {
            $bookinfo = $bookModel->getBook($bid, 1);
            if ($bookinfo === false) {
                $output['message'] = '找不到要收藏的书籍';
                $this->ajaxReturn($output);
            }
        } else {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        /* 查询收藏信息，如果已收藏则更新，否则直接把书籍插入收藏
         * 更新bookmark $bookinfo的last_updatechptitle、last_updatechpid、last_updatetime(以\t连接)
         * */
        $favinfo = $bookModel->getFavCount($uid, $bid);
        if (is_array($favinfo)) {
            $bookmark             = array();
            //$tmpmark = $bookinfo['last_updatechptitle'] . '\t' . $bookinfo['last_updatechpid'] . '\t' . $bookinfo['last_updatetime'];
            $tmpmark              = $bookinfo['last_updatechptitle'] . "\t" . $bookinfo['last_updatechpid'] . "\t" . NOW_TIME;
            $bookmark['bookmark'] = $tmpmark;
            $favObj               = M('fav');
            $savemap              = array("fid" => $favinfo['fid']);
            $res                  = $favObj->where($savemap)->save($bookmark);
        } else {
            $bcModel      = M('BookshelfCategory');
            $fmap         = array("uid" => $uid);
            $categoryinfo = $bcModel->where($fmap)->find();
            if (!$categoryinfo) {
                $data       = array(
                    'uid'           => $uid,
                    'category_name' => '默认书架'
                );
                $categoryid = $bcModel->add($data);
                if (!$categoryid) {
                    $output['message'] = "未知错误";
                    $this->ajaxReturn($output);
                }
            } else {
                $categoryid = $categoryinfo['category_id']; //category_id为空
            }
            //$tmpmark = $bookinfo['last_updatechptitle'] . '\t' . $bookinfo['last_updatechpid'] . '\t' . $bookinfo['last_updatetime'];
            $tmpmark  = $bookinfo['last_updatechptitle'] . "\t" . $bookinfo['last_updatechpid'] . "\t" . NOW_TIME;
            $userinfo = session();
            $_old = C('TOKEN_ON');
            C('TOKEN_ON', false);
            $res      = $bookModel->addFav($userinfo, $bid, $tmpmark, $categoryid); //返回的是该条记录的id
            C('TOKEN_ON', $_old);
        }

        if ($res) {
            saveBookFavCookie(1,$bid);
            //添加粉丝积分,收藏50积分
            $integral          = C("INTEGRAL.fav");
            $fansModel         = new \Client\Model\FensiModel();
            $fansModel->addFansIntegral($bid, $uid, $integral);
            $output['status']  = 1;
            $output['message'] = '收藏成功';
        } else {
            $output['message'] = '收藏失败';
        }
        $this->ajaxReturn($output);
    }

    /**
     * 删除收藏
     *
     * @param int $bid 书籍id get
     * @param int $uid 用户id session
     *
     * @return string 删除陈宫/失败信息
     *      needlogin字段用来告知客户端是否需要登录(=1需要登录，=0不需要登录)--元气萌客户端开始启用
     * */
    public function _delfav() {
        $output = array("status" => 0, "message" => "", "url" => "", "needlogin"=>0);
        $uid    = isLogin();
        if (!$uid) {
            $output['needlogin'] = 1;
            $output['message'] = '请先登录';
            $output['url']     = url('User/login', array(), 'do');
            $this->ajaxReturn($output);
        }
        $bid = I('get.bid', 0, 'intval');
        if ($bid) {
            $bookModel = new \Client\Model\BookModel();
            $res       = $bookModel->delFav($uid, $bid);
            if (!$res) {
                $output['message']  = '删除失败';
                $output['totalnum'] = 0;
                $this->ajaxReturn($output);
            } else {
                saveBookFavCookie(0,$bid);
                $output['status']   = 1;
                $output['message']  = '删除成功';
                $output['url']      = url('User/shelf', array(), 'do');
                $output['totalnum'] = 0;
                $this->ajaxReturn($output);
            }
        } else {
            $output['message']  = '参数错误';
            $output['totalnum'] = 0;
            $this->ajaxReturn($output);
        }
    }

    /**
     * 自动注册接口(android & IOS)
     *
     */
    public function _autoregister() {
        $output = array("status" => 0, "message" => "", "url" => "");
        if (!in_array(CLIENT_NAME, array("ios", "android"))) {
            $output['message'] = "参数错误";
            $this->ajaxReturn($output);
        }
        $action           = I("param.action", "", "trim");
        $reids_autoid_key = C("cache_prefix") . ":THIRD_CLIENT_IN_ONE:autouserid";
        S(C("rdconfig"));
        $result           = S($reids_autoid_key);
        if (!$result) {
            S($reids_autoid_key, 100);
        }
        if ($action == "getkey") {
            /* 获取缓存key */
            print_r(S($reids_autoid_key));
            exit;
        } else if ($action == "get") {
            /* 保存注册 */
            $uuid   = $this->deviceInfo['UUID'];
            $device = $this->deviceInfo['device'];
            if (empty($uuid) || empty($device)) {
                $output['message'] = '错误代码1';
                $this->writewronglogs($output);
                $this->ajaxReturn($output);
            }
            $device_title = $device;
            $device       = md5($device);
            //SELECT uid FROM wis_android_autouser WHERE uuid='{$uuid}' AND device='{$device}'
            $tabName      = CLIENT_NAME . "_autouser";
            $where        = array(
                "uuid"   => $uuid,
                "device" => $device,
            );
            $userModel    = new \Client\Model\UserModel();
            $userinfo     = M($tabName)->where($where)->find();
            if ($userinfo) {
                $weuserinfo          = $userModel->getUserbyUid($userinfo['uid']);
                if(strtolower(CLIENT_NAME) === 'ios' && CLIENT_VERSION >= '2.0.0'){
                    $usercode = $userModel->generate_authcode($weuserinfo, $uuid);
                }else{
                    $usercode = $userModel->generate_authcode($weuserinfo);
                }
                //登录
                $userModel->login($weuserinfo['uid'], 365 * 24 * 3600);
                ///返回值
                $output['status']    = 1;
                $output['message']   = "ok";
                $output['usercode']  = $usercode;
                $output['uid']       = $weuserinfo['uid'];
                $output['username']  = $weuserinfo['username'];
                $output['nickname']  = $weuserinfo['nickname'];
                $output['viplevel']  = $weuserinfo['viplevel'];
                $output['groupid']   = $weuserinfo['groupid'];
                $output['money']     = $weuserinfo['money'];
                $output['avatar']    = getUserFaceUrl($weuserinfo['uid']);
                $output['groupname'] = C('USERGROUP')[$weuserinfo['groupid']]['title'];
                $this->ajaxReturn($output);
            }
            //$defaultgroupid=1;
            if (strstr($this->M_forward, url("User/login")) || strstr($this->M_forward, url("User/register"))) {
                $this->M_forward = '/';
            }
            for ($i = 0; $i < 5; $i++) {
                $username    = C('SITECONFIG.AUTO_USERNAME_PREFIX') . mt_rand(100, 999); // . '_';      //下划线在手机上不方便输入，暂时屏蔽
                $last_autoid = S($reids_autoid_key);
                if (!$last_autoid || $last_autoid < 833100) {
                    $last_autoid = 833100;
                }
                $tmp          = $last_autoid + 1;
                S($reids_autoid_key, $tmp);
                $username     = $username . $last_autoid;
                //$msg_username = userObj()->we_check_username($username);
                $msg_username = $userModel->we_check_username($username);
                if ($msg_username == "ok") {
                    $msgmap       = array("nickname" => $username);
                    $msg_userinfo = M("read_user")->where($msgmap)->find();
                    if ($msg_userinfo) {
                        ;
                    } else {
                        break;
                    }
                }
            }
            //检测用户名
            $usermap  = array(
                "username" => $username,
            );
            $readuser = M("read_user")->where($usermap)->find();
            if ($readuser) {
                $output['message'] = '错误:用户名已经存在';
                $this->writewronglogs($output);
                $this->ajaxReturn($output);
            }
            $msg_username = $userModel->we_check_username($username);
            if ($msg_username != 'ok') {
                $output['message'] = $msg_username;
                $this->writewronglogs($output);
                $this->ajaxReturn($output);
            }
            //检测email
            $email       = 'hsatus_' . CLIENT_NAME . '_'.mt_rand(100, 999). '_'. $last_autoid . '@hongshu.com';
            $isgoodemail = isValidEmail($email);
            if (!$isgoodemail) {
                $output['message'] = "<font color=red>X</font>错误:邮件地址格式不对";
                $this->writewronglogs($output);
                $this->ajaxReturn($output);
            }
            $usedemail = $userModel->checkEmailExist($email);
            if ($usedemail) {
                $output['message'] = "<font color=red>X</font>错误:已经有人使用该EMAIL注册过帐户" . $email;
                $this->writewronglogs($output);
                $this->ajaxReturn($output);
            }
            //检测密码
            $password     = 'iehS@!3d8';
            $repassword   = $password;
            $msg_password = $userModel->we_check_password($password, $repassword);
            if ($msg_password != 'ok') {
                $output['message'] = $msg_password;
                $this->ajaxReturn($output);
            }
            //注册
            $md5password = $userModel->pwdEncrypt($password);
            $weuid       = $userModel->add($username, $md5password, $email);
            //初始化$weuserinfo
            $weuserinfo  = false;
            //根据$weuid获取用户数组，如果不存在则用$username获取，如果不存在则报错
            if ($weuid) {
                $weuserinfo = $userModel->getUserbyUid($weuid);
            } else {
                $wemap      = array("username" => $username);
                $weuserinfo = M("user")->where($wemap)->find();
            }
            //是否是作者
            if ($weuserinfo) {
                $isauthor = $userModel->checkAuthorByUid($weuid);
                if ($isauthor) {
                    $weuserinfo['isauthor'] = 1;
                } else {
                    $weuserinfo['isauthor'] = 0;
                }
            } else {
                $output['message'] = "错误：您上次提交的注册已经完成,请勿重复提交";
                $this->writewronglogs($output);
                $this->ajaxReturn($output);
            }
            //写CLIENT_NAME."_autouser"表
            $data                = array(
                "uid"          => $weuserinfo['uid'],
                "device"       => $device,
                "uuid"         => $uuid,
                "device_title" => $device_title,
            );
            $autouserid          = M($tabName)->add($data);
            //登录
            $userModel->login($weuserinfo['uid'], 3600 * 24 * 365);
            //设置登录状态
            if(strtolower(CLIENT_NAME) === 'ios' && CLIENT_VERSION >= '2.0.0'){
                $usercode = $userModel->generate_authcode($weuserinfo, $uuid);
            }else{
                $usercode = $userModel->generate_authcode($weuserinfo);
            }
            ///返回值
            $output['status']    = 1;
            $output['message']   = "ok";
            $output['usercode']  = $usercode;
            $output['uid']       = $weuserinfo['uid'];
            $output['username']  = $weuserinfo['username'];
            $output['nickname']  = $weuserinfo['nickname'];
            $output['viplevel']  = $weuserinfo['viplevel'];
            $output['groupid']   = $weuserinfo['groupid'];
            $output['money']     = $weuserinfo['money'];
            $output['avatar']    = getUserFaceUrl($weuserinfo['uid']);
            $output['groupname'] = C('USERGROUP')[$weuserinfo['groupid']]['title'];
            $output['isauthor']  = $weuserinfo['isauthor'];
            $this->ajaxReturn($output);
        }
    }

    /**
     * 客户端自动注册接口写错误日志
     *
     */
    protected function writewronglogs($output) {
        //_write(C("M_ROOT")."data/logs/".CLIENT_NAME."_autoreg_err.logs",implode('||', $output)."\n","a+");
        \Think\Log::write(implode('||', $output), 'ERROR', '', LOG_PATH . CLIENT_NAME . "_autoreg_err.logs");
    }

    /**
     * 2.15    测用户校验码是否有效(login.php/action=checklogin)
     *
     *
     */
    public function _checkusercode() {

        $usercode  = I("param.P30", "", "trim");
        $userModel = new \Client\Model\UserModel();
        $uid       = isLogin();
        if ($uid) {
            $userinfo = array();

            $userinfo           = $userModel->getUserbyUid($uid);
            //头像地址
            $userinfo['avatar'] = getUserFaceUrl($userinfo['uid']);
            //是否是作者
            $isauthor           = $userModel->checkAuthorByUid($userinfo['uid']);
            if ($isauthor) {
                $userinfo['isauthor'] = 1;
            } else {
                $userinfo['isauthor'] = 0;
            }
            //会员等级
            $userinfo['groupname'] = C('USERGROUP')[$userinfo['groupid']]['title'];
            //返回数据
            $userinfo['status']    = 1;
            $userinfo['message']   = "ok";
            $this->ajaxReturn($userinfo);
        } else {
            $userModel->doLogout();
            $output = array(
                'status'  => 0,
                'message' => '请登录',
                'url'     => url('User/login', array(), 'do'),
            );
            $this->ajaxReturn($output);
        }
    }

    /**
     * 客户端登录接口
     *
     * @param string $userName 账号 post
     * @param string $passWord 密码 post
     */
    public function _logininterface() {
        $output   = array(
            "status"  => 0,
            "message" => "",
            "url"     => "",
        );
        //用户名
        $userName = I("post.username", "", "trim");
        if (!$userName) {
            $output['message'] = "用户名不能为空";
            $this->ajaxReturn($output);
        }
        //密码
        $passWord = I("post.password", "", "trim");
        if (!$passWord) {
            $output['message'] = '密码不能为空';
            $this->ajaxReturn($output);
        }
        //是否记录登录状态
        $rememberLogin = I("post.remember", 0, "intval");
        $userModel     = new \Client\Model\UserModel();
        $result        = $userModel->loginByUsernamePassword($userName, $passWord, $rememberLogin);
        if ($result) {
            $output = $result;
        } else {
            $output['message'] = $userModel->getError();
            if (!$output['message']) {
                $output['message'] = '登录失败！';
            }
        }
        $this->ajaxReturn($output);
    }

    /**
     * 客户端注册接口
     *
     * @param string $userName 用户名 post
     * @param string $password 密码 post
     * @param string $email 邮箱 post
     *
     * @return 成功/失败的信息
     */
    public function _registerinterface() {
        $output = array('status' => 0, 'message' => '', 'url' => '');
        if ($this->M_forward == url("User/login") || $this->M_forward == url("User/register")) {
            $this->M_forward = "/";
        }
        $userModel = new \Client\Model\UserModel();
        //用户名
        $userName  = I("post.username", "", "trim");
        if (!$userName) {
            $output['message'] = '用户名不能为空';
            $this->ajaxReturn($output);
        }
        $msg_username = $userModel->we_check_username($userName);
        if ($msg_username != "ok") {
            $output['message'] = $msg_username;
            $this->ajaxReturn($output);
        }
        $readmap  = array("username" => $userName);
        $readname = M("read_user")->where($readmap)->find();
        if (is_array($readname) && $readname) {
            $output['message'] = "错误:用户名已经存在";
            $this->ajaxReturn($output);
        }
        $nickmap  = array("nickname" => $userName);
        $readnick = M("read_user")->where($nickmap)->find();
        if (is_array($readnick) && $readnick) {
            $output['message'] = "错误:用户名不能用别人的昵称";
            $this->ajaxReturn($output);
        }
        //密码
        $password     = $repassword   = I("post.password", "", "trim");
//         if(!$password){
//             $password = $repassword = "d1e2f3";
//         }
        $msg_password = $userModel->we_check_password($password, $repassword);
        if ($msg_password != "ok") {
            $output['message'] = $msg_password;
            $this->ajaxReturn($output);
        }
        //email
        $email     = I("post.email", "", "trim");
//         if(!$email){
//             $email = "d1e2f3";
//         }
        $msg_email = $userModel->we_check_email($email);
        if ($msg_email != 'ok') {
            $output['message'] = $msg_email;
            $this->ajaxReturn($output);
        }
        //添加注册
        $uid = $userModel->add($userName, $password, $email);
        if ($uid) {
            $userinfo            = $userModel->getUserbyUid($uid);
            $usercode            = $userModel->generate_authcode($userinfo);
            $isauthor            = $userModel->getAuthorByUid($uid);
            //设置登录状态
            $userModel->login($userinfo['uid']);
            //groupname
            $groupname           = C("USERGROUP." . $userinfo['groupid'] . ".title");
            session("groupname", $groupname);
            //返回值
            $output['status']    = 1;
            $output['message']   = "ok";
            $output['usercode']  = $usercode;
            $output['uid']       = $userinfo['uid'];
            $output['username']  = $userinfo['username'];
            $output['nickname']  = $userinfo['nickname'];
            $output['viplevel']  = $userinfo['viplevel'];
            $output['groupid']   = $userinfo['groupid'];
            $output['isauthor']  = $isauthor ? $isauthor : 0;
            $output['avatar']    = getUserFaceUrl($uid);
            $output['groupname'] = $groupname;
            $output['money']     = $userinfo['money'];
            $this->ajaxReturn($output);
        } else {
            $output['message'] = "注册失败";
            $this->ajaxReturn($output);
        }
    }

    /**
     * 获取用户红点数和红点位置、获取用户签到状态
     *
     * @param string $uuid 设备信息
     * @param string $method 检查红点或检查签到，checkpoint：检查红点，checkqiandao:检查签到
     *
     * @return array json数组
     */
    public function _checkpointqiandao() {
        $needpoint = 0;
        $uuid      = $this->deviceInfo['UUID'];
        if (!$uuid) {
            exit(0);
        }
        $thisday = date("ymd");

        //区分android、ios表
        if (CLIENT_NAME && in_array(CLIENT_NAME, array('ios', 'android'))) {
            $tablename = CLIENT_NAME . '_qiandao';
        }
        $tmpmap       = array(
            "uuid"        => $uuid,
            "qiandao_day" => $thisday,
        );
        $linjianginfo = M($tablename)->field('egold')->where($tmpmap)->fetchSql(FALSE)->select();

        if (!$linjianginfo || count($linjianginfo) <= 0) {
            $is_need_qiandao = 1;
        } else {
            $is_need_qiandao = 0;
        }

        $method = I('post.method', '', 'trim');
        if ($method == 'checkpoint') {
            $needpoint += $is_need_qiandao;
            $this->ajaxReturn(array('b1' => $needpoint));
        } elseif ($method == 'checkqiandao') {
            $this->ajaxReturn(array('is_need_qiandao' => $is_need_qiandao));
        }
    }

    /**
     * yqm读者注册
     * @param string $username 用户帐号 post
     * @param string $contactway 联系方式 post
     * @param string $password 密码 post
     * @param int $yzm 验证码 post
     */
    public function _readerReg_yqm() {
        $output   = array('status' => 0, 'message' => '', 'url' => '');
        $username = I('post.username', '', 'trim');
//         $contactway = I('post.contactway','','trim');
        $password = I('post.password', '', 'trim');
        $yzm      = I('post.yzm', '', 'intval');

        if (!$username || !$password || !$yzm) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        //验证码
        $verifyModel = new \HS\Verify();
        $yzmres      = $verifyModel->check($yzm);
        if ($yzmres <= 0) {
            $output['message'] = '验证码错误';
            $this->ajaxReturn($output);
        }
        //检测用户名和密码长度
        $namelen = strlen($username);
        if ($namelen < 2 || $namelen > 15) {
            $output['message'] = '账号过短或过长';
            $this->ajaxReturn($output);
        }
        $pwdlen = strlen($password);
        if ($pwdlen < 6 || $pwdlen > 15) {
            $output['message'] = '密码过短或过长';
            $this->ajaxReturn($output);
        }
//         $emailres = isValidEmail($contactway);
//         $phoneres = isValidPhone($contactway);
//         //联系方式
//         if($emailres && $phoneres){
//             $output['message'] = '联系方式错误';
//             $this->ajaxReturn($output);
//         }
        //username
        $userModel = new \Client\Model\UserModel();
        $nameres   = $userModel->checkUserNameExist($username);
        if ($nameres) {
            $output['message'] = '用户名已存在';
            $this->ajaxReturn($output);
        }
//         $email = '';
//         $phone = '';
//         if(!$emailres){
//             $email = $contactway;
//             $emailexits = $userModel->checkEmailExist($email);
//             if($emailexits){
//                 $output['message'] = '邮箱已存在';
//                 $this->ajaxReturn($output);
//             }
//         }else if($phoneres){
//             $phone = $contactway;
//             $phoneexits = $userModel->checkPhoneExist($phone);
//             if($phoneexits){
//                 $output['message'] = '手机已存在';
//                 $this->ajaxReturn($output);
//             }
//         }
        //添加用户
        $uid = $userModel->add($username, $password);
        if ($uid === false) {
            $output['message'] = '注册失败';
            $this->ajaxReturn($output);
        }
        //设置登录状态
        $res = $userModel->login($uid,0);
        $output['status']  = 1;
        $output['message'] = '注册成功';
        if (stristr($this->M_forward, url("User/login")) || stristr($this->M_forward, url("User/register")) || !$this->M_forward) {
            $output['url'] = url('User/shuquan', array(), 'do');
        } else {
            $output['url'] = $this->M_forward;
        }
        $this->ajaxReturn($output);
    }

    /**
     * 作者注册
     *
     * @param string $username post
     * @param string $password post
     * @param int $qq post
     * @param int $phone post
     * @param string $email post
     * @param string $authorname post
     * @param int $fromuid 推荐人id post
     */
    public function _authorReg() {
        $output     = array('status' => 0, 'message' => '', 'url' => '');
        $username   = I('post.username', '', 'trim');
        $password   = I('post.password', '', 'trim');
        $qq         = I('post.qq', 0, 'intval');
        $phone      = I('post.phone', 0, 'intval');
        $email      = I('post.email', '', 'trim');
        $authorname = I('post.authorname', '', 'trim');
        $fromuid    = I("post.fromuid", 0, "intval");
        if (!$username || !$password || !$qq || !$phone || !$email || !$authorname) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        //一天内注册次数缓存key
        S(C('rdconfig'));
        $regcountkey = "regcount" . get_client_ip();
        $regcount    = S($regcountkey);
        if ($regcount > 3) {
            $output['message'] = '您注册的次数过于频繁';
            $this->ajaxReturn($output);
        }
        //username
        if (!preg_match("/^[A-z0-9]{2,15}+$/u", $username)) {
            $output['message'] = '用户名格式错误';
            $this->ajaxReturn($output);
        }
        //password
        $pwdlen = strlen($password);
        if ($pwdlen < 6 || $pwdlen > 15) {
            $output['message'] = '密码格式错误';
            $this->ajaxReturn($output);
        }
        //$authorname
        $authornamelen = strLength($authorname);
        if ($authornamelen < 2 || $authornamelen > 8) {
            $output['message'] = '笔名格式错误';
            $this->ajaxReturn($output);
        }
        //手机
        if (!isValidPhone($phone)) {
            $output['message'] = '手机号错误';
            $this->ajaxReturn($output);
        }
        //email
        if (!isValidEmail($email)) {
            $output['message'] = '邮箱错误';
            $this->ajaxReturn($output);
        }
        //检测用户名是否重复
        $userModel = new \Client\Model\UserModel();
        $userexits = $userModel->checkUserNameExist($username);
        if ($userexits) {
            $output['message'] = '账号已存在';
            $this->ajaxReturn($output);
        }
        //检测笔名是否含有坏词
        $badwords  = $userModel->getAuthorNameBreakWords();
        $isbadword = $userModel->checkBreakword($badwords, $authorname);
        if ($isbadword) {
            $output['message'] = "您的笔名中含有违禁词";
            $output = array_merge($output, $isbadword);
            $this->ajaxReturn($output);
        }
        //笔名是否重复
        $authorexits = $userModel->checkAuthorNameExist($authorname);
        if ($authorexits) {
            $output['message'] = '笔名已存在';
            $this->ajaxReturn($output);
        }
        //邮箱重复
        $emailexits = $userModel->checkEmailExist($email);
        if ($emailexits) {
            $output['message'] = '邮箱已存在';
            $this->ajaxReturn($output);
        }
        //电话重复
        $phoneexits = $userModel->checkPhoneExist($phone);
        if ($phoneexits) {
            $output['message'] = '电话已存在';
            $this->ajaxReturn($output);
        }
        //分配责编
        if ($fromuid) {
            $zebianinfo = $userModel->getzeRenBianjibyUid($fromuid);
        }
        if (!$zebianinfo) {
            $zebianinfo = $userModel->getRollzeBian("nan");
        }
        if (CLIENT_NAME == 'myd' && !$zebianinfo){
            $zebianinfo = $userModel->getRollzeBian('nv');
        }
        if (!$zebianinfo || !is_array($zebianinfo)) {
            $output['message'] = '注册失败，请稍后再试';
            $this->ajaxReturn($output);
        }
        //开始事物
        $userModel->startTrans();
        $uid = $userModel->add($username, $password, $email, $phone);
        if ($uid) {
            $authorid = $userModel->addAuthor($uid, $authorname, $qq, $phone, $email, $zebianinfo['uid']);
            if ($authorid) {
                //提交事务,并设置登录状态
                $userModel->commit();
                //一天只能注册3次
                $now      = NOW_TIME;
                $tomorrow = strtotime(date('Y-m-d', strtotime('+1 day')));
                $expire   = $tomorrow - $now;
                if ($regcount) {
                    S($regcountkey, intval($regcount) + 1, $expire);
                } else {
                    S($regcountkey, 1, $expire);
                }
                $user     = $userModel->getAuthorByUid($uid);
                $isauthor = 0;
                if ($user && is_array($user)) {
                    $isauthor   = 1;
                    $authorid   = $user['authorid'];
                    $authorname = $user['authorname'];
                }
                $user['isauthor']   = $isauthor;
                $user['authorid']   = $authorid;
                $user['authorname'] = $authorname;
                $user['islogin']    = true;
                $user['uid']        = $uid;
                foreach ($user as $k => $vo) {
                    session($k, $vo);
                }
//                 session($user);
                $output['status']  = 1;
                $output['message'] = "恭喜，注册成功";
                $fu                = I("get.fu", "", "trim,removeXSS");
                if ($fu) {
                    $output['url'] = $fu;
                } else {
                    $output['url'] = url("User/authorLogin", array("sign" => 1), 'do');
                }
                $this->ajaxReturn($output);
            } else {
                $userModel->rollback();
                $output['message'] = '注册失败';
            }
        } else {
            $userModel->rollback();
            $output['message'] = '注册失败,请稍后再试';
        }
        $this->ajaxReturn($output);
    }

    /**
     * 作者注册账号是否存在检测
     * @param string $username post
     */
    public function _checkAuthorExits_myd(){
        $output     = array('status' => 0, 'message' => '');
        $username   = I('post.username', '', 'trim');//检测用户名是否重复
        if (!$username){
            $output['message'] = '账号不得为空';
            $this->ajaxReturn($output);
        }
        if (!preg_match("/^[A-z0-9]{2,15}+$/u", $username)) {
            $output['message'] = '账号格式错误';
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        $userexits = $userModel->checkUserNameExist($username);
        if ($userexits) {
            $output['message'] = '账号已存在';
            $this->ajaxReturn($output);
        }else{
            $output['status'] = 1;
            $output['message'] = '账号可以使用';
        }
        $this->ajaxReturn($output);
    }
    /**
     * 已有帐号注册作者
     *
     * @param string $authornick 作者昵称 post
     * @param int $mobile 手机 post
     * @param int $qq qq号 post
     * @param string $email 邮箱 post
     * @param int $fromuid 推荐人id post
     */
    public function _authorRegWithReferee() {
        $output = array("status" => 0, "message" => "", "url" => "");
        //检测登录
        $uid    = isLogin();
        if (!$uid) {
            $output['message'] = '请先登录';
            $output['url']     = url("User/login", array(), 'do');
            $this->ajaxReturn($output);
        }
        $authornick = I("post.authorname", "", "trim");
        $mobile     = I("post.phone", 0, "intval");
        $qq         = I("post.qq", 0, "intval");
        $email      = I("post.email", "", "trim");
        $fromuid    = I("post.fromuid", 0, "intval");
//         $sexflag = I("post.sexflag","","trim");
        if (!$authornick || !$mobile || !$qq || !$email) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        //验证手机号是否合法
        $ismobile = isValidPhone($mobile);
        if (!$ismobile) {
            $output['message'] = '手机号码错误，请重新输入';
            $this->ajaxReturn($output);
        }
        //验证邮箱是否合法
        $isemail = isValidEmail($email);
        if (!$isemail) {
            $output['message'] = '邮箱输入错误，请重新输入';
            $this->ajaxReturn($output);
        }
        $userModel    = new \Client\Model\UserModel();
        //验证手机是否已存在
        $mobileExists = $userModel->checkPhoneExist($mobile);
        if ($mobileExists) {
            $output['message'] = '手机已注册，请更换未注册的手机号码';
            $this->ajaxReturn($output);
        }
        //验证邮箱是否存在
        $emailExists = $userModel->checkEmailExist($email);
        if ($emailExists) {
            $output['message'] = '邮箱已注册，请更换未注册的邮箱';
            $this->ajaxReturn($output);
        }
        //验证作者昵称
        $breakwords = $userModel->getAuthorNameBreakWords();
        $badword    = $userModel->checkBreakword($breakwords, $authornick);
        if ($badword) {
            $output['message'] = '您的昵称含有非法词语请更改';
            $this->ajaxReturn($output);
        }
        $nickExists = $userModel->checkAuthorNameExist($authornick);
        if ($nickExists) {
            $output['message'] = "昵称已存在";
            $this->ajaxReturn($output);
        }
        //分配编辑
        if ($fromuid) {
            $zebianInfo = $userModel->getzeRenBianjibyUid($fromuid);
        }
        if (!$zebianInfo) {
            $zebianInfo = $userModel->getRollzeBian("nan");
        }
        if (!$zebianInfo) {
            $output['message'] = '参数错误，请稍后再试';
            $this->ajaxReturn($output);
        }
        $authorid = $userModel->addAuthor($uid, $authornick, $qq, $mobile, $email, $zebianInfo['uid']);
        if ($authorid) {
            $output['status']  = 1;
            $output['message'] = '恭喜，注册成功';
            $fu                = I("get.fu", "", "trim,removeXSS");
            if ($fu) {
                $output['url'] = $fu;
            } else {
                $output['url'] = url("User/authorLogin", array("sign" => 1), 'do');
            }
        } else {
            $output['message'] = "注册失败，请稍后再试";
        }
        $this->ajaxReturn($output);
    }
    /**
     * pc站登录
     */
    public function _login_www(){
        $this->_login_html5();
    }
    /**
     * pc站注册
     */
    public function _pcregister(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $username = I('post.username','','trim');
        $password = I('post.password','','trim');
        $name = I('post.name','','trim');//真是姓名
        $imgcode = I('post.yzm',0,'intval'); //验证码
        $sfz = I('post.sfz','','trim'); //身份证
        if(!$username || !$password || !$name || !$imgcode || !$sfz){
            $output['message'] = '注册信息不完整！';
            $this->ajaxReturn($output);
        }
        //验证验证码
        if (intval(session('imgcode')) != $imgcode) {
            $output['message'] = '验证码错误！';
            $this->ajaxReturn($output);
        }
        //验证身份证
        $validsfz = $this->validateIDCard($sfz);
        if(!$validsfz){
            $output['message'] = '请输入正确的身份证号码！';
            $this->ajaxReturn($output);
        }
        //验证姓名2-4个汉字
        if(!preg_match('/^[\x80-\xff]{4,8}$/',$name)){
            $output['message'] = '请输入正确的真实姓名！';
            $this->ajaxReturn($output);
        }
        if(check_badword(cached_badword(false, 864000, 'username'), $username)){
            $output['message'] = '真实姓名不能含有违禁词！';
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        //验证密码6-15位
        if(!preg_match("/^[\w~!@#%&*]{6,15}/", $password)){
            $output['message'] = '密码不符合和规范！';
            $this->ajaxReturn($output);
        }
        //验证用户名
        $usernamemsg = $userModel->we_check_username($username);
        if($usernamemsg != 'ok'){
            $output['message'] = $usernamemsg;
            $this->ajaxReturn($output);
        }
        $usernameexists = $userModel->checkUserNameExist($username);
        if(intval($usernameexists) > 0){
            $output['message'] = '用户名已重复！';
            $this->ajaxReturn($output);
        }
        //插入数据库
        $uid = $userModel->add($username,$password,'','');
        if(intval($uid) > 0){
            //设置登录状态
            $output = $userModel->loginByUsernamePassword($username,$password);
            $output['status'] = 1;
            $output['message'] = '恭喜您，注册成功！';
            $output['url'] = $this->M_forward;
        }else{
            $output['message'] = '注册失败，请重试';
        }
        $this->ajaxReturn($output);
    }
    
    //验证身份证是否有效
    private function validateIDCard($IDCard) {
        if (strlen($IDCard) == 18) {
            return $this->check18IDCard($IDCard);
        } elseif ((strlen($IDCard) == 15)) {
            $IDCard = $this->convertIDCard15to18($IDCard);
            return $this->check18IDCard($IDCard);
        } else {
            return false;
        }
    }
    //计算身份证的最后一位验证码,根据国家标准GB 11643-1999
    private function calcIDCardCode($IDCardBody) {
        if (strlen($IDCardBody) != 17) {
            return false;
        }
    
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $code = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
    
        for ($i = 0; $i < strlen($IDCardBody); $i++) {
            $checksum += substr($IDCardBody, $i, 1) * $factor[$i];
        }
    
        return $code[$checksum % 11];
    }
    // 将15位身份证升级到18位
    private function convertIDCard15to18($IDCard) {
        if (strlen($IDCard) != 15) {
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($IDCard, 12, 3), array('996', '997', '998', '999')) !== false) {
                $IDCard = substr($IDCard, 0, 6) . '18' . substr($IDCard, 6, 9);
            } else {
                $IDCard = substr($IDCard, 0, 6) . '19' . substr($IDCard, 6, 9);
            }
        }
        $IDCard = $IDCard . $this->calcIDCardCode($IDCard);
        return $IDCard;
    }
    // 18位身份证校验码有效性检查
    private function check18IDCard($IDCard) {
        if (strlen($IDCard) != 18) {
            return false;
        }
    
        $IDCardBody = substr($IDCard, 0, 17); //身份证主体
        $IDCardCode = strtoupper(substr($IDCard, 17, 1)); //身份证最后一位的验证码
    
        if ($this->calcIDCardCode($IDCardBody) != $IDCardCode) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * 元气萌-书圈（书籍列表）
     * (所有评论过的书籍)
     */
    public function _shuQuanBooks(){
        $pagenum = I("get.".C('VAR_PAGE'),1,'intval');
        $totalnum = I('get.totalnum',0,'intval');
        $pagesize = I('get.pagesize',8,'intval');
        $output = array('status'=>0,'message'=>'','url'=>'','pagenum'=>$pagenum,'totalnum'=>$totalnum);
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $output['url'] = url('User/login',array(),'do');
            $this->ajaxReturn($output);
        }
        $comModel = new \Client\Model\NewcommentModel();
        $bookModel = new \Client\Model\BookModel();
        $userMap = array('uid' => $uid);
        $bids = $comModel->field('distinct bid')->where($userMap)->order('creation_date DESC')->select();
        if(!$bids){
            $output['message'] = '暂无记录';
            $this->ajaxReturn($output);
        }
        if(!$totalnum){
            $totalnum = count($bids);
        }
        $pageModel = new \HS\Pager($totalnum,$pagesize);
        $clientMethod = I('get.clientmethod','','trim');
        if($clientMethod){
            $pageModel->clientmethod = $clientMethod;
        }
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next','>');
        $pageModel->setConfig('first','|<');
        $pageModel->setConfig('last','>|');
        $pagelist = $pageModel->show();
        if($totalnum > $pagesize){
            $pagebids = array_slice($bids,$pageModel->firstRow, $pageModel->listRows);
        }else{
            $pagebids = $bids;
        }
        //获取书籍信息
        $books = array();
        $leftbids = array(); //如缓存中评论总数为0，则收集bid，然后查表
        foreach($pagebids as $bid){
            if($bid && $bid['bid']){
                $book = $bookModel->getBook($bid['bid']);
            }else{
                continue;
            }
            //检查书籍是否可以展示
            if(!checkBook($book,false)){
                continue;
            }
            $books[$bid['bid']] = $book;
            $commentCache = $comModel->getCommentByBid($bid['bid']); //取缓存评论总数
            $books[$bid['bid']]['totalcomment'] = intval($commentCache['totalnum'])?intval($commentCache['totalnum']):0;
            //收集没有评论总数的bid
            if(!$books[$bid['bid']]['totalcomment']){
                $leftbids[] = $bid['bid'];
            }
        }
        if(!$books){
            $output['message'] = '暂无记录.';
            $this->ajaxReturn($output);
        }
        //查询没有评论总数的书籍的评论总数
        if($leftbids){
            $bidstr = implode(',',$leftbids);
            $nmap = array(
                'bid'=>array('IN',$bidstr),
                'deleted_flag'=>array('neq',1),
                'content'=>array('neq',''),
                array(
                    'forbidden_flag'=>array('neq',1),
                    'uid'=>$uid,
                    '_logic'=>'OR',
                ),
            );
            $res = M('newcomment')->field('bid,count(comment_id) as totalnum')->where($nmap)->group('bid')->select();
            if($res){
                //拼入评论总数
                foreach($res as $v){
                    $books[$v['bid']]['totalcomment'] = intval($v['totalnum']);
                }
            }
        }
        $output['status'] = 1;
        $output['booklist'] = $books;
        $output['pagelist'] = $pagelist;
        $output['pagenum'] = $pageModel->nowPage;
        $output['totalnum'] = $pageModel->totalRows;
        $this->ajaxReturn($output);
    }
    /**
     * 元气萌-书圈（我发起的评论）
     *
     * @param int $pagelistsize （每页显示多少页码，用来计算当前页的起始页码，喵阅读）
     */
    public function _myComments(){
        $pagenum = I("get.".C('VAR_PAGE'),1,'intval');
        $totalnum = I('get.totalnum',0,'intval');
        $pagesize = I('get.pagesize',4,'intval');
        $clientMethod = I('get.clientmethod','','trim');
        $pageListSize = I('get.pagelistsize',5,'intval'); 
        $output = array('status'=>0,'message'=>'','url'=>'','pagenum'=>$pagenum,'totalnum'=>$totalnum);
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $output['url'] = url('User/login',array(),'do');
            $this->ajaxReturn($output);
        }
        $comModel = new \Client\Model\NewcommentModel();
        $map = array(
            'uid' => $uid,
            'deleted_flag' => 0,
            'content'=>array('neq',''),
            array(
                'forbidden_flag'=>array('neq',1),
                'uid'=>$uid,
                '_logic'=>'OR',
            ),
        );
        if(!$totalnum){
            $totalnum = $comModel->where($map)->count();
        }
        if(!$totalnum){
            $output['message'] = '暂无记录，请稍后再试';
            $this->ajaxReturn($output);
        }
        $pageModel = new \HS\Pager($totalnum,$pagesize);
        if($clientMethod){
            $pageModel->clientmethod = $clientMethod;
        }
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next', '>');
        $pageModel->setConfig('first','|<');
        $pageModel->setConfig('last','>|');
        $pagelist = $pageModel->show();
        $cmap = array(
            'c.uid'=>$uid,
            'c.deleted_flag'=>0,
            'c.content'=>array('neq',''),
            array(
                'c.forbidden_flag'=>array('neq',1),
                'c.uid'=>$uid,
                '_logic'=>'OR',
            ),
        );
        $comments = $comModel->alias('c')->join('__BOOK__ AS b on b.bid = c.bid','left')->
                    field('c.comment_id,c.title,c.content,c.creation_date,c.reply_amount,c.zan_amount,c.forbidden_flag,b.bid,b.catename')->
                    where($cmap)->order('c.creation_date DESC')->limit($pageModel->firstRow,$pageModel->listRows)->select();

        if(!$comments){
            $output['message'] = '暂无记录';
            $this->ajaxReturn($output);
        }
        foreach($comments as &$comment){
            if(!trim($comment['content'])){
                unset($comment);
                continue;
            }
            $comment['creation_date'] = date('Y/m/d',$comment['creation_date']);
        }
        //喵阅读计算当前页的起始页码
        if(CLIENT_NAME == 'myd'){
            $pageliststart = (ceil($pagenum/$pageListSize) -1) * $pageListSize + 1;
            $output['pageliststart'] = $pageliststart;
        }

        $output['status'] = 1;
        $output['commentlist'] = $comments;
        $output['pagelist'] = $pagelist;
        $output['pagenum'] = $pageModel->nowPage;
        $output['totalnum'] = $totalnum;
        $output['totalpage'] = $pageModel->totalPages;
        $this->ajaxReturn($output);
    }
    /**
     * 元气萌-书圈（我回复的评论）
     */
    public function _myReplyComments(){
        $pagenum = I("get.".C('VAR_PAGE'),1,'intval');
        $totalnum = I('get.totalnum',0,'intval');
        $pagesize = I('get.pagesize',4,'intval');
        $clientMethod = I('get.clientmethod','','trim');
        $output = array('status'=>0,'message'=>'','url'=>'','pagenum'=>$pagenum,'totalnum'=>$totalnum);
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $output['url'] = url('User/login',array(),'do');
            $this->ajaxReturn($output);
        }
        $subMap = array('uid'=>$uid);
        $subSql = M('newcomment_reply')->field('distinct comment_id')->where($subMap)->order('creation_date')->buildSql();
        $cmap = array(
            'comment_id'=>array('exp',' IN '.$subSql),
            'forbidden_flag'=>0,
            'deleted_flag'=>0,
        );
        if(!$totalnum){
            $totalnum = M('newcomment')->where($cmap)->count();
        }
        if(!$totalnum){
            $output['message'] = '暂无记录';
            $this->ajaxReturn($output);
        }
        $pageModel = new \HS\Pager($totalnum,$pagesize);
        if($clientMethod){
            $pageModel->clientmethod = $clientMethod;
        }
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next', '>');
        $pageModel->setConfig('first','|<');
        $pageModel->setConfig('last','>|');
        $pagelist = $pageModel->show();
        $res = M('newcomment')->field('comment_id,title,content,reply_amount,zan_amount,creation_date,bid,forbidden_flag')->
                where($cmap)->limit($pageModel->firstRow,$pageModel->listRows)->select();
        if(!$res){
            $output['message'] = '暂无记录，请稍后再试';
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        foreach($res as &$vo){
            $vo['creation_date'] = date('Y/m/d',$vo['creation_date']);
            $bookinfo = array();
            $bookinfo = $bookModel->getBook($vo['bid']);
            if(!$bookinfo){
                unset($vo);
            }
            $vo['catename'] = $bookinfo['catename'];
        }
        $output['status'] = 1;
        $output['commentlist'] = $res;
        $output['pagelist'] = $pagelist;
        $output['pagenum'] = $pageModel->nowPage;
        $output['totalpage'] = $pageModel->totalPages;
        $output['totalnum'] = $totalnum;
        $this->ajaxReturn($output);
    }
    /**
     * 元气萌-书圈(回复我的评论)
     * 即我的评论的回复内容
     */
    public function _myCommentReplies(){
        $pagenum = I("get.".C('VAR_PAGE'),1,'intval');
        $totalnum = I('get.totalnum',0,'intval');
        $pagesize = I('get.pagesize',4,'intval');
        $clientMethod = I('get.clientmethod','','trim');
        $output = array('status'=>0,'message'=>'','url'=>'','pagenum'=>$pagenum,'totalnum'=>$totalnum);
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $output['url'] = url('User/login',array(),'do');
            $this->ajaxReturn($output);
        }
        $cmap = array(
            'uid'=>$uid,
            'forbidden_flag'=>0,
            'deleted_flag'=>0,
            'content'=>array('neq','')
        );
        $subSql = M('newcomment')->field('comment_id')->where($cmap)->buildSql();
        $rmap = array(
            'r.comment_id'=>array('exp',' IN '.$subSql),
            'r.delete_flag'=>0,
            'r.content'=>array('neq',''),
            'r.isread'=>0,
            array(
                'r.forbidden_flag'=> 0,
                'r.uid' => $uid,
                '_logic'=>'OR'
            )
        );
        if(!$totalnum){
            $totalnum = M('newcomment_reply')->alias('r')->where($rmap)->count();
        }
        if(!$totalnum){
            $output['message'] = '暂无记录';
            $this->ajaxReturn($output);
        }
        $pageModel = new \HS\Pager($totalnum,$pagesize);
        if($clientMethod){
            $pageModel->clientmethod = $clientMethod;
        }
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next', '>');
        $pageModel->setConfig('first','|<');
        $pageModel->setConfig('last','>|');
        $pagelist = $pageModel->show();
        $res = M('newcomment_reply')->alias('r')->join('__BOOK__ AS b on b.bid=r.bid','left')->
                field('r.reply_id,r.comment_id,r.content,r.username,r.nickname,r.creation_date,r.isread,r.support,r.reply_amount,b.bid,b.catename')->
                where($rmap)->order('r.creation_date DESC,r.isread DESC')->limit($pageModel->firstRow,$pageModel->listRows)->select();
        if(!$res){
            $output['message'] = '暂无记录.';
            $this->ajaxReturn($output);
        }
        foreach($res as &$vo){
            $vo['creation_date'] = date('Y/m/d',$vo['creation_date']);
            $vo['nickname'] = $vo['nickname']?$vo['nickname']:$vo['username'];
        }
        //喵阅读计算当前页的起始页码
        if(CLIENT_NAME == 'myd'){
            $pageListSize = I('get.pagelistsize',5,'intval');
            $pageliststart = (ceil($pagenum/$pageListSize) -1) * $pageListSize + 1;
            $output['pageliststart'] = $pageliststart;
        }
        
        $output['status'] = 1;
        $output['commentlist'] = $res;
        $output['pagelist'] = $pagelist;
        $output['pagenum'] = $pageModel->nowPage;
        $output['totalpage'] = $pageModel->totalPages;
        $output['totalnum'] = $totalnum;
        $this->ajaxReturn($output);
    }
    /**
     * yqm检测红点、评论有未读回复
     * @param int $uid session
     *
     */
    public function _getRedDot(){
        $uid = isLogin();
        $output = array('status'=>0,'isred'=>0,'num'=>0);
        if(!$uid){
            $this->ajaxReturn($output);
        }
        //TODO:查询收藏书籍是否有更新

        //查询未读回复
        $map = array(
            'n.uid'=>$uid,
            'n.deleted_flag'=>array('neq',1),
            'n.content' => array('neq',''),
            'n.forbidden_flag'=>array('neq',1),
        );
//         $subsql = M('newcomment')->field('comment_id')->where($map)->buildSql();
//         $rmap = array(
//             'comment_id'=>array('exp',' IN '.$subsql),
//             'isread'=>0,
//         );
//         $replies = M('newcomment_reply')->field('comment_id')->where($rmap)->find();
        if(CLIENT_NAME == 'myd'){
            //喵阅读需要取总条数
            $replies = M('newcomment')->alias('n')->join('__NEWCOMMENT_REPLY__ AS r on r.comment_id = n.comment_id AND r.isread = 0')->
                        field('r.comment_id')->where($map)->count();
        }else{
            $replies = M('newcomment')->alias('n')->join('__NEWCOMMENT_REPLY__ AS r on r.comment_id = n.comment_id AND r.isread = 0')->
                        field('r.comment_id')->where($map)->find();
        }
        if($replies){
            $output['status'] = 1;
            $output['isred'] = 1;
            $output['num'] = $replies;
        }
        $this->ajaxReturn($output);
    }
    /**
     * 兑换红薯卡
     * @param $cardno 卡号 POST
     * 
     */
    public function changecardAction(){
        $output = array('status'=>0,'message'=>'','url'=>'','amount'=>0);
        $cardno = I('post.card','','trim');
        if(!$cardno){
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        //每月第一天凌晨0点至早11点时红薯卡兑换暂停
        $day = date("d",NOW_TIME);
        $hours = date("H",NOW_TIME);
        if ($day == '01' && $hours < 11){
            $output['message'] = '对不起，兑换暂停，请稍后再试';
            $this->ajaxReturn($output);
        }
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $output['url'] = url('User/login','','do');
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        $userinfo = $userModel->find($uid);
        //获取红薯卡
        $cardinfo = $userModel->getCardBycardno($cardno);
        if(!$cardinfo || !is_array($cardinfo)){
            $output['message'] = '对不起，卡号不存在，请查看卡号是否正确输入！';
            $this->ajaxReturn($output);
        }
        if ($cardinfo['isusered'] == 1) {
            //已兑换
            $output['message'] = '该卡已使用，请检查卡号是否正确输入';
            $this->ajaxReturn($output);
        } else {
            $thismonth = date("Ym");
            if ($cardinfo['expiredate'] < $thismonth) {
                //已过期
                $output['message'] = '该卡已过期，请检查卡号是否正确输入';
                $this->ajaxReturn($output);
            }
            if ($cardinfo['expiredate'] > $thismonth) {
                //本月不能兑换该卡,请到兑换期时兑换
                $output['message'] = '对不起，未到兑换日期';
                $this->ajaxReturn($output);
            }
            if ($cardinfo['uid'] && $cardinfo['uid'] != session('uid')) {
                //请勿兑换其他人的卡
                $output['message'] = '不能使用他人的卡，请检查卡号是否正确输入';
                $this->ajaxReturn($output);
            }
        }
        //兑换,加锁根据卡号
        $key = "card_".$cardno;
        $lockModel = new \HS\CacheLock($key);
        if(!$lockModel->lock()){
            $output['message'] = '兑换正在进行，请耐心等待';
            $this->ajaxReturn($output);
        }
        $res = $userModel->duihuanHsCard($userinfo, $cardinfo);
        $lockModel->unlock();
        unset($lockModel);
        if($res){
            $output['status'] = 1;
            $output['message'] = '兑换成功';
            $output['amount'] = intval($cardinfo['cardnum']);
        }else{
            $output['message'] = '对不起，兑换失败，请稍后再试';
        }
        $this->ajaxReturn($output);
    }
    /**
     * 元气萌ios登录
     * @param string $username
     * @param string $pwd
     * @param int $vericode 验证码
     * 
     * @return json
     */
    public function _yqmclientlogin(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $username = I('post.username','','trim');
        $pwd = I('post.password','','trim');
        $vericode = I('post.vericode',0,'intval');
        if(!$username || !$pwd){
            $output['message'] = '请输入用户名或密码';
            $this->ajaxReturn($output);
        }
        //设置缓存记录用户是否是第一次登录
        $key = ':yqmclientlogin:'.$username;
        $cacheModle = new \HS\MemcacheRedis();
        $lognum = $cacheModle->get($key) ? $cacheModle->get($key) : 0;
        //不是第一次登录则验证验证码
        if($lognum){
            if(!$vericode){
                $output['message'] = '请输入验证码';
                $this->ajaxReturn($output);
            }else{
                if(CLIENT_NAME == 'ios'){
                    $vcodeKey = ':imgcode:'.$username;
                    $vcode = $cacheModle->getMc($vcodeKey);
                    if(!$vcode || $vcode != $vericode){
                        $output['message'] = '验证码错误';
                        $this->ajaxReturn($output);
                        } 
                }else{
                    $output['message'] = '验证码错误';
                    $this->ajaxReturn($output);
                }
            }
        }
        $userModel = new \Client\Model\UserModel();
        $uuid = $this->deviceInfo['UUID'];
        //手机号登录
        if(isValidPhone($username)){
            $res = $userModel->loginByUsernamePassword($username,$pwd,1,3,0,$uuid);
        }
        //用户名登录
        if(!$res){
            $res = $userModel->loginByUsernamePassword($username,$pwd,1,0,0,$uuid);
        }
        if($res){
            unset($res['status']);
            unset($res['message']);
            $output['status'] = 1;
            $output['message'] = '登录成功';
            $output['userinfo'] = $res;
            $output['url'] = $this->M_forward == 'webview' ? url("User/index",'','do') : $this->M_forward ? removeXSS($this->M_forward) : '/'; 
        }else{
            $cacheModle->setMc($key, 1);
            $output['message'] = '登录失败，请重试';
        }
        $this->ajaxReturn($output);
    }
    /**
     * 用户名、密码摘要登录
     * @param string $username  post
     * @param string $remark    post
     */
    public function _loginWithRemark(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $username = I('post.username','','trim');
        $remark = I('post.remark','','trim');
        if(!$username || !$remark){
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        $uuid = $this->deviceInfo['UUID'];
        //验证密码摘要
        $where = array();
        if(isValidPhone($username)){
            $where = array('mobile'=>$username);
        }else{
            $where = array('username'=>$username);
        }
        $pwdFromRemark = $userModel->checkRemark($remark,$where);
        if($pwdFromRemark){
            if(isValidPhone($username)){
                $userinfo = $userModel->loginByUsernamePassword($username,$pwdFromRemark,0,3,2,$uuid);
            }
            //用用户名登录
            if(!$userinfo){
                $userinfo = $userModel->loginByUsernamePassword($username,$pwdFromRemark,0,0,2,$uuid);
            }
            if($userinfo){
                $output['status'] = 1;
                $output['userinfo'] = $userinfo;
                $output['url'] = $this->M_forward == 'webview' ? url("User/index",'','do') : $this->M_forward ? removeXSS($this->M_forward) : '/';
            }else{
                if($userModel->getError()){
                    $output['message'] = $userModel->getError();
                }else{
                    $output['message'] = '登录失败，请重试';
                }
            }
        }else{
            $output['message'] = '摘要错误';
        }
        $this->ajaxReturn($output);
    }
    /**
     * 喵阅读，申请版主、副版主
     * @param int type(=1版主，=2副版主)  get
     * @param int bid   get
     * 
     */
    public function _applyBanzhu(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $bid = I('get.bid',0,'intval');
        $type = I('get.type',0,'intval');
        if($type !== 1 && $type !== 2){
            $output['message'] = '参数错误！';
            $this->ajaxReturn($output);
        }
        $uid = isLogin();

        if(!$uid){
            $output['message'] = '请先登录';
            $this->ajaxReturn($output);
        }
        if(!$bid){
            $output['message'] = '参数错误,没有书籍信息';
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        $bookinfo = $bookModel->getBook($bid);
        if(!$bookinfo || !is_array($bookinfo) || $bookinfo['copyright'] != 3){
            $output['message'] = '对不起，找不到该书';
            $this->ajaxReturn($output);
        }
        $userinfo = session();
        $isAdmin=false;
        $isAuthor=false;
        $isBanzhu=false;
        $isFubanzhu=false;
        $commentModel = new \Client\Model\NewcommentModel();
        $commentModel->flush_comment_set($bid);
        $comment_set = $commentModel->get_comment_set_cache($bid);
        $comment_set['banzhu'] = json_decode($comment_set['banzhu'],true);
        $comment_set['fubanzhu'] = json_decode($comment_set['fubanzhu'],true);
        $userModel = new \Client\Model\UserModel();
        if ($userModel->checkPriv('编辑', $userinfo)) {
            $isAdmin = true;
        } elseif (isset($userinfo['authorid']) && $userinfo['authorid'] == $bookinfo['authorid']) {
            $isAuthor = true;
        } elseif (isset($comment_set['banzhu'][$uid])) {
            $isBanzhu = true;
        } elseif (isset($comment_set['fubanzhu'][$uid])) {
            $isFubanzhu = true;
        }
        //副版主以上权限不需要申请
        if($isAdmin || $isAuthor || $isBanzhu || $isFubanzhu){
            $output['message'] = '您不需要申请助理';
            $this->ajaxReturn($output);
        }
        //判断用户所在组是否允许评论
        if(C('USERGROUP.'.$userinfo['groupid'].'.cancomment') < 1){
            $output['message'] = '您所在的用户组不允许评论，无法申请助理';
            $this->ajaxReturn($output);
        }
        //用户是否被禁言
        if($commentModel->checkKillUser($bid, $uid)){
            $output['message'] = '您已被禁言，无法申请助理';
            $this->ajaxReturn($output);
        }
        //检查版主或副版主是否已满员
        if($type == 1 && $comment_set['banzhu'] >= 1){
            $output['message'] = '助理人数已满，请下回赶早~';
            $this->ajaxReturn($output);
        }elseif($type == 2 && $comment_set['fbanzhu'] >= 3){
            $output['message'] = '副助理已满员，请下回赶早~';
            $this->ajaxReturn($output);
        }
        //是否已申请过版主
        $map = array(
            'bid'=>$bid,
            'uid'=>$uid
        );
        $banzhuInfo = $commentModel->getBanzhuInfo($map);
        if($banzhuInfo){
            if ($banzhuInfo['sq_status'] == 2) {
                $output['message'] = '对不起，您的申请已被拒绝，原因：'.$banzhuInfo['admin_desc'];
            } elseif ($banzhuInfo['sq_status'] == 3) {
                $output['message'] = '恭喜，您的申请已通过，请刷新页面';
            } else {
                $output['message'] = '您的申请正在审核，请耐心等待';
            }
            $this->ajaxReturn($output);
        }
        //检查发帖数
        $userComments = $commentModel->getCommentByUid($uid,100);
        if($userComments && is_array($userComments)){
            $totalnum = count($userComments);
            $jinghuanum = 0;
            foreach($userComments as $vo){
                if($vo['highlight_flag'] == 1){
                    $jinghuanum ++;
                }
            }
            if($totalnum < 100 || $jinghuanum < 5){
                $output['message'] = '您不符合申请条件,申请失败,请继续努力!要求:发帖数100条以上或精华帖5条以上';
                $this->ajaxReturn($output);
            }
        }else{
            $output['message'] = '您不符合申请条件,申请失败,请继续努力!要求:发帖数100条以上或精华帖5条以上';
            $this->ajaxReturn($output);
        }
        $res = $commentModel->addBanzhu($bid, $uid, $type);
        if($res){
            $output['status'] = 1;
            $output['message'] = '恭喜，申请成功，请耐心等待审核';
        }else{
            $output['message'] = '申请失败，请稍后再试';
        }
        $this->ajaxReturn($output);
    }
    /**
     * 审核,取消版主
     * @param string $operate 操作(agree=通过,deny=拒绝,del=取消) post
     * @param int $id 申请记录id post
     * @param int $bid post
     * @param int $banzhuuid 版主uid（取消版主操作）
     * @param int $type (取消版主类型,=1版主，=2副版主)
     */
    public function _reviewBanzhu(){
        $output=array('status'=>0,'message'=>'','url'=>'');
        $operate = I('post.operate','','trim');
        $id = I('post.id',0,'intval');
        $bid = I('post.bid',0,'intval');
        if(!in_array($operate, array('agree','deny','del')) || !$bid){
            $output['message'] = '参数错误！';
            $this->ajaxReturn($output);
        }
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        $bookinfo = $bookModel->getBook($bid);
        if(!$bookinfo || !is_array($bookinfo)){
            $output['message'] = '找不到书籍信息';
            $this->ajaxReturn($output);
        }
        $userinfo = session();
        $isAuthor = false;
        $isAdmin = false;
        $isBanzhu = false;
        $userModel = new \Client\Model\UserModel();
        if ($userModel->checkPriv('编辑', $userinfo)) {
            $isAdmin = true;
        } elseif (isset($userinfo['authorid']) && $userinfo['authorid'] == $bookinfo['authorid']) {
            $isAuthor = true;
        } elseif (isset($comment_set['banzhu'][$uid])) {
            $isBanzhu = true;
        }
        $commentModel = new \Client\Model\NewcommentModel();
        if(in_array($operate,array('agree','deny'))){
            if(!$id){
                $output['message'] = '参数错误，找不到该记录';
                $this->ajaxReturn($output);
            }
            $map = array('sq_id'=>$id);
            $banzhuInfo = $commentModel->getBanzhuInfo($map);
            if(!$banzhuInfo){
                $output['message'] = '找不到该记录';
                $this->ajaxReturn($output);
            }
            $type = $banzhuInfo['sq_type'];
            //审核版主
            $commentModel->flush_comment_set($bid);
            $comment_set = $commentModel->get_comment_set_cache($bid);
            $comment_set['banzhu'] = json_decode($comment_set['banzhu'],true);
            $comment_set['fubanzhu'] = json_decode($comment_set['fubanzhu'],true);
            //版主可以审核副版主的申请
            if($type == 2){
                if(!$isAdmin && !$isAuthor && !$isBanzhu){
                    $output['message'] = '权限不足,无法审核该申请';
                    $this->ajaxReturn($output);
                }
            }else{
                //作者和编辑可以审核主编
                if(!$isAdmin && !$isAuthor){
                    $output['message'] = '对不起，权限不足';
                    $this->ajaxReturn($output);
                }
            }
            if($operate == 'agree'){
                if($type == 1 && $comment_set['banzhu'] >= 1){
                    $output['message'] = '助理人数已满';
                    $this->ajaxReturn($output);
                }elseif ($type == 2 && $comment_set['fbanzhu'] >= 3){
                    $output['message'] = '副助理人数已满';
                    $this->ajaxReturn($output);
                }
                //已拒绝的则不能再通过
                if($banzhuInfo['sq_status'] == 2){
                    $output['message'] = '申请已被拒绝';
                    $this->ajaxReturn($output);
                }
                $sq_status = 3;
                $reason = 'uid='.$uid.','.$userinfo['nickname'].','.date("Y-m-d H:i:s",NOW_TIME).',通过了该申请';
            }else{
                //已通过的则不能再拒绝
                if($banzhuInfo['sq_status'] == 3){
                    $output['message'] = '申请已通过';
                    $this->ajaxReturn($output);
                }
                $sq_status = 2;
                $reason = 'uid='.$uid.','.$userinfo['nickname'].','.date("Y-m-d H:i:s",NOW_TIME).',拒绝了申请';
            }
            $where = array('sq_id'=>$id);
            $data = array('sq_status'=>$sq_status,'admin_desc'=>$reason);
            $res = $commentModel->updateBanzhu($where,$data);
            if($res){
                $output['status'] = 1;
                $output['message'] = '审核成功';
            }else{
                $output['message'] = '对不起，操作失败';
            }
        } elseif ($operate == 'unset'){
            $banzhuuid = I('post.banzhuuid',0,'intval');
            if(!$banzhuuid){
                $output['message'] = '参数错误，找不到该助理';
                $this->ajaxReturn($output);
            }
            $type = I('post.type',0,'intval');
            if(!$type){
                $output['message'] = '参数错误，无法确定助理等级';
                $this->ajaxReturn($output);
            }
            //取消版主
            if(!$isAdmin && !$isAuthor){
                $output['message'] = '对不起，权限不足';
                $this->ajaxReturn($output);
            }
            $where = array(
                'bid'=>$bid,
                'uid'=>$banzhuuid,
                'sq_type'=>$type
            );
            $banzhuinfo = $commentModel->getBanzhuInfo($where);
            if(!$banzhuinfo){
                $output['message'] = '没有该助理';
                $this->ajaxReturn($output);
            }
            if($type == 1){
                $reason = 'uid:'.$uid.','.$userinfo['nickname'].'于'.date("Y-m-d H:i:s",NOW_TIME).'取消了版主权限';
            }else{
                $reason = 'uid:'.$uid.','.$userinfo['nickname'].'于'.date("Y-m-d H:i:s",NOW_TIME).'取消了副版主权限';
            }
            $data = array(
                'sq_status'=> 2,
                'admin_desc' => $reason,
            );
            $res = $commentModel->updateBanzhu($where, $data);
            if($res){
                $output['status'] = 1;
                $output['message'] = '操作成功！';
            }else{
                $output['message'] = '操作失败!';
            }
        } else {
            $output['message'] = '参数错误，请稍后重试';
        }
        $this->ajaxReturn($output);
    }
    /**
     * 邮箱找回密码:发邮件
     * @param string $email post
     */
    public function _sendEmail(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $email = I('post.email','','trim');
        //验证邮箱
        if(!$email || !isValidEmail($email)){
            $output['message'] = '邮箱错误，请重试';
            $this->ajaxReturn($output);
        }
        //根据邮箱获取用户信息
        $userModel = new \Client\Model\UserModel();
        $userinfo = $userModel->getUserByEmail($email);
        if(!$userinfo || !is_array($userinfo)){
            $output = '对不起，邮箱不存在';
            $this->ajaxReturn($output);
//             $userinfo = $userModel->find(2529346);
        }
        //验证码
        $validcode = \Org\Util\String::randString(6, 5);
        $userModel->setPwdValidCode($userinfo['uid'], $validcode);
        $url = url('User/setPassword',array('id'=>base64_encode($userinfo['uid']),'code'=>$validcode),'do',true);
        $client = new \GearmanClient();
            $gearmans = C('GEARMAN');
            foreach($gearmans as $gearman) {
                $client->addServer($gearman['host'], $gearman['port']);
            }
        $body = iconv("UTF-8", "GBK//IGNORE", '<table width="700" border="0" align="center" cellspacing="0" style="width:700px;"><tbody><tr><td><div style="width:700px;margin:0 auto;border-bottom:1px solid #ccc;margin-bottom:30px;">'
            . '<table border="0" cellpadding="0" cellspacing="0" width="700" height="39" style="font:12px Tahoma, Arial, 宋体;"><tbody><tr><td width="210"></td></tr></tbody></table></div><div style="width:680px;padding:0 10px;margin:0 auto;">'
            . '<div style="line-height:1.5;font-size:14px;margin-bottom:25px;color:#4d4d4d;"><strong style="display:block;margin-bottom:15px;">'
            . '尊敬的' . C('CLIENT.'.CLIENT_NAME.'.name') . '用户：<span style="color:#f60;font-size: 16px;"></span>您好！</strong><strong style="display:block;margin-bottom:15px;">'
            . '您正在重置密码，请跳转到以下链接：<br/><br/>'
            . '<span style="color:#f60;font-size: 18px;">'
            . '<a target="_blank" href="' . $url . '">' . $url . '</a>'
            . '</span><br/><br/>以完成操作。</strong></div><div style="margin-bottom:30px;"><small style="display:block;margin-bottom:20px;font-size:12px;"><p style="color:#747474;">'
            . '注意：此操作可能会修改您的密码、登录邮箱或绑定手机。如非本人操作，请及时登录并修改密码以保证帐户安全<br/>（工作人员不会向你索取此链接，请勿泄漏！)</p></small></div></div>'
            . '<div style="width:700px;margin:0 auto;"><div style="padding:10px 10px 0;border-top:1px solid #ccc;color:#747474;margin-bottom:20px;line-height:1.3em;font-size:12px;"><p>此为系统邮件，请勿回复<br/>'
            . '请保管好您的邮箱，避免账号被他人盗用</p><p>' . C('CLIENT.'.CLIENT_NAME.'.name') . '版权所有2008-' . date('Y') . '</p></div></div></td></tr></tbody></table>'
            );
        $From = iconv("UTF-8", "GBK//IGNORE", C('CLIENT.'.CLIENT_NAME.'.name'));
        $Subject = iconv("UTF-8", "GBK//IGNORE", C('CLIENT.'.CLIENT_NAME.'.name') . "用户邮箱验证");
        $options = array('From' => $From, 'FromName' => $From, 'CharSet' => "GBK", 'Subject' => $Subject, '');
        
        $options['AltBody'] = "To view the message, please use an HTML compatible email viewer!";
        $options['SendtoName'] = $userinfo['username'];
        $options['SendtoEmail'] = $userinfo['email'];
        $options['body'] = $body;
        $client->doBackground("gearmanWork_sendemail", serialize($options), 'lostpwdemail_' . $userinfo['uid'] . '-' . date("Y-m-d-H"));
        $output['status'] = 1;
        $output['message'] = '邮件发送成功！';
        if(C('APP_DEBUG')){
            $output['url'] = $url;
        }
        $this->ajaxReturn($output);
    }
    /**
     * 邮箱找回密码
     * @param string $id base64_encode的uid post
     * @param string $code base64_encode的验证码 post
     * @param string $password 新密码 post
     */
    public function _findPasswordByEmail(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $id = I('post.id','','trim');
        $code = I('post.code','','trim');
        $password = I('post.password','','trim');
        if(!$id || !$code || !$password){
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        if(strlen($password) < 6 || strlen($password) > 15){
            $output['message'] = '密码过长或过短';
            $this->ajaxReturn($output);
        }
        $uid = base64_decode($id);
        $validCode = base64_decode($code);
        $userModel = new \Client\Model\UserModel();
        $cacheCode = $userModel->getPwdValidCode($uid);
        //验证验证码
        if(!$cacheCode || $cacheCode != $validCode){
            $output['message'] = '链接已失效，请重新获取';
            $output['url'] = url('User/losepwd',array(),'do');
            $this->ajaxReturn($output);
        }
        $res = $userModel->setPassword($uid, $password);
        if($res){
            $output['status'] = 1;
            $output['message'] = '密码重置成功，请登录';
            $output['url'] = url('User/login',array(),'do');
        }else{
            $output['message'] = '密码重置失败';
        }
        $this->ajaxReturn($output);
    }
    /**
     * 喵阅读，获取书架书籍
     * @param int $pagenum get
     * @param int $pagesize get
     * @param int $totalnum get
     * @param int $cateid 书架id（=0则取全部收藏书籍） get
     * @param strint $order 排序方式(=read按阅读时间，=update按更新时间，=buy按订阅时间)
     * @param string $clientmethod 分页方法
     * 
     * @return array
     *  是否阅读过 isread
     *  自动订阅 autoorder
     *  总章节数totalchapters
     *  最后阅读章节序号lastreadchporder
     *  最新章节lastupdatetitle
     *  最后更新时间lastupdatetime
     *  最后阅读的章节名lastreadchptitle
     *  最后阅读章节是否是vip ischaptervip
     *  书籍是否是vip isbookvip
     */
    public function _getShelfList_myd(){
        $output = array('status'=>0,'message'=>'','list'=>array(),'url'=>'');
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $this->ajaxReturn($output);
        }
        $pagenum = I('get.pagenum',1,'intval');
        $pagesize = I('get.pagesize',10,'intval');
        $totalnum = I('get.totalnum',0,'intval');
        $clientmethod = I('get.clientmethod', '', 'trim');
        $cateid = I('get.cateid',0,'intval');
        $order = I('get.order','read','trim');
        if(!in_array($order,array('read','update','buy'))){
            $order = 'read';
        }
        $where = array();
        $where['uid'] = $uid;
        if($cateid){
            $where['category_id'] = $cateid;
        }
        $bookModel = new \Client\Model\BookModel();
        if(!$totalnum){
            $totalnum = $bookModel->getFavBookCount($where);
        }
        if($totalnum < 1){
            $output['message'] = '暂无数据';
            $this->ajaxReturn($output);
        }
        $pageModel = new \HS\Pager($totalnum, $pagesize);
        if ($clientmethod) {
            $pageModel->clientmethod = $clientmethod;
        }
        $pagestr = $pageModel->show();
        //分页：当前页显示的页码起始点.....
        $pageliststart = (ceil($pagenum/10) -1) * 10 + 1;
        //取阅读记录=>获取最后阅读章节
        $cookiebooks = getcookiefavary(cookie('favs'));
        //获取已订阅书籍
        $orderMap = array('uid'=>$uid);
        $autoOrderBooks = $bookModel->getAutoOderBooks($orderMap, 'bid');
        if($autoOrderBooks && is_array($autoOrderBooks)){
            $autoOderBids = array_column($autoOrderBooks, 'bid');
        }else{
            $autoOrderBids = array();
        }
        $list = $bookModel->getFavBook($where, $pageModel->firstRow, $pageModel->listRows);
        foreach($list as $key=>$val){
            if($autoOderBids && in_array($val['bid'], $autoOderBids)){
                $list[$key]['autoorder'] = 1;
            }else{
                $list[$key]['autoorder'] = 0;
            }
            //封面
            $list[$key]['cover'] = getBookfacePath($val['bid']);
            //书籍是否是vip
            $list[$key]['isbookvip'] = $val['isvip'];
            $chapterlist = $bookModel->getChplistByBid($val['bid']);
            $list[$key]['totalchapters'] = count($chapterlist['list']);
            //最后更新章节
            $lastupdatechapter = array_pop($chapterlist['list']);
            $list[$key]['lastupdatetime'] = friendly_date($lastupdatechapter['publishtime']);
            $list[$key]['lastupdatetitle'] = $lastupdatechapter['title'];
            //有阅读记录，则取阅读记录的章节，否则取第一章
            if(isset($cookiebooks[$val['bid']])){
                $list[$key]['isread'] = 1;
                $lastreadchapter = array();
                foreach($chapterlist['list'] as $chapter){
                    if($chapter['chapterid'] == $cookiebooks[$val['bid']]['chapterid']){
                        $lastreadchapter = $chapter;
                        break;
                    }
                }
            }else{
                $list[$key]['isread'] = 0;
                $lastreadchapter = array_shift($chapterlist['list']);
            }
            $list[$key]['lastreadchptitle'] = $lastreadchapter['title'];
            $list[$key]['lastreadchporder'] = $lastreadchapter['chporder'];
            $list[$key]['ischaptervip'] = $lastreadchapter['isvip'];
        }
        if($order == 'read'){
            //阅读时间(如果有阅读记录按阅读记录排序，否则保持原样)
            if($cookiebooks){
                $orderBooks = array();
                $cookiebooks = array_reverse($cookiebooks);
                foreach ($cookiebooks as $val){
                    foreach ($list as $k => $vo){
                        if($val['bid'] == $vo['bid']){
                            $orderBooks[] = $vo;
                            unset($list[$k]);
                        }
                    }
                }
                if($list){
                    $list = array_merge($orderBooks, $list);
                }else{
                    $list = $orderBooks;
                }
            }
        }elseif($order == 'update'){
            //按更新时间
            $lastupdatetime = array_column($list,'lastupdatetime');
            array_multisort($list,SORT_DESC,$lastupdatetime);
        }else{
            //按订阅时间
            $saletimes = array();
            foreach($list as $kk => $vv){
                $lastsaletime = $bookModel->getLastDingGouTime($uid, $vv['bid']);
                $list[$kk]['lastsaletime'] = $lastsaletime;
                $saletimes[] = $lastsaletime;
            }
            array_multisort($list,SORT_DESC,$saletimes);
        }
        
        $output['status'] = 1;
        $output['list'] = $list;
        $output['pageliststart'] = $pageliststart;
        $output['pagenum'] = $pagenum;
        $output['totalpage'] = $pageModel->totalPages;
        $output['totalnum'] = $pageModel->totalRows;
        $this->ajaxReturn($output);
    }
    /**
     * 保存用户信息设置,可单独修改昵称或密码
     * @param string $nickname 可选 post
     * @param string $newpassword 可选 post
     * @param string $oldpassword 可选 post
     */
    public function _setUserInfo(){
        $output = array('status'=>1,'message'=>'','url'=>'');
        $newpassword = I('post.newpassword','','trim');
        $oldpassword = I('post.oldpassword','','trim');
        $nickname = I('post.nickname','','trim');
        $nickname = trim(preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $nickname)); //过滤表情
        if(!$nickname && !$newpassword && !$oldpassword){
            $output['message'] = '参数错误！';
            $this->ajaxReturn($output);
        }
        if(($newpassword && !$oldpassword) || (!$newpassword && $oldpassword)){
            $output['message'] = '修改密码需要原密码和新密码';
            $this->ajaxReturn($output);
        }
        $this->check_user_login();
        $uid = isLogin();
        if(!$uid){
            $output['message'] = '请先登录';
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        $userinfo = $userModel->find($uid);
        if(!$userinfo || !is_array($userinfo)){
            $output['message'] = '系统错误，请稍后再试';
            $this->ajaxReturn($output);
        }
        //修改昵称
        if($nickname){
            //检查是否已经修改昵称
            if($userinfo['nickallow']){
                $output['message'] = '昵称只能修改一次，请勿重复修改';
                $this->ajaxReturn($output);
            }
            //检测昵称已否已被占用
            if($userModel->checkNickNameExist($nickname)){
                $output['message'] = '昵称已被使用，请更换其他昵称';
                $this->ajaxReturn($output);
            }
        }
        //修改密码
        if($newpassword && $oldpassword){
            if($newpassword == $oldpassword){
                $output['message'] = '新密码不能与原密码相同';
                $this->ajaxReturn($output);
            }
            $encOldPassword = $userModel->pwdEncrypt($oldpassword);
            if($encOldPassword !== $userinfo['password']){
                $output['message'] = '原密码错误';
                $this->ajaxReturn($output);
            }
        }
        $res = $userModel->userSet($uid, $nickname, '', '', $newpassword, '', '');
        if($res){
            if($nickname){
                //更新session的昵称
                session('nickname',$nickname);
            }
            $output['status'] = 1;
            $output['message'] = '设置成功！';
        }else{
            $output['message'] = '设置失败！';
        }
        $this->ajaxReturn($output);
    }
    /**
     * 喵阅读，注册
     * @param string $username (2-15位) post
     * @param string $phoneormail(6-15位) post
     * @param string $password post
     * @param int $imgcode post 验证码
     */
    public function _registerForUser(){
        $output = array('status'=>1,'message'=>'','url'=>'');
        $username = I('post.username','','trim');
        $phoneOrMail = I('post.phoneormail','','trim');
        $password = I('post.password','','trim');
        $imgcode = I('post.imgcode','','trim');
        if(!$username || !$phoneOrMail || !$password || !$imgcode){
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        //验证验证码
        if($imgcode != session('imgcode')){
            $output['message'] = '验证码错误';
            $this->ajaxReturn($output);
        }
        //验证用户名
        $userModel = new \Client\Model\UserModel();
        $checkUsername = $userModel->we_check_username($username);
        if($checkUsername != 'ok'){
            $output['message'] = $checkUsername;
            $this->ajaxReturn($output);
        } elseif ($userModel->checkUserNameExist($username)){
            $output['message'] = '用户名已存在';
            $this->ajaxReturn($output);
        }
        //验证联系方式
        if (isValidPhone($phoneOrMail)) {
            if ($userModel->checkPhoneExist($phoneOrMail)) {
                $output['message'] = '手机号已存在';
                $this->ajaxReturn($output);
            }
        } elseif (isValidEmail($phoneOrMail)){
            if ($userModel->checkEmailExist($phoneOrMail)) {
                $output['message'] = '邮箱已存在';
                $this->ajaxReturn($output);
            }            
        } else {
            $output['message'] = '邮箱或手机错误，请重新输入';
            $this->ajaxReturn($output);
        }
        //验证密码
        if(strlen($password) < 6 || strlen($password) > 15){
            $output['message'] = '密码过长或过短';
            $this->ajaxReturn($output);
        }
        if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $password)){
            $output['message'] = '密码不能含有中文';
            $this->ajaxReturn($output);
        }
        //添加注册
        if(isValidPhone($phoneOrMail)){
            $res = $userModel->add($username, $password, '', $phoneOrMail);
        } else {
            $res = $userModel->add($username, $password, $phoneOrMail, '');
        }
        if($res){
            //设置登录状态
            $userModel->loginByUsernamePassword($username, $password);
            $output['status'] = 1;
            $output['message'] = '恭喜您，注册成功';
            $output['url'] = url("User/index", array(), 'do');
        }else{
            $output['message'] = '对不起，注册失败，请重试';
        }
        $this->ajaxReturn($output);
    }

    /**
     * 已经注册过喵阅读账号的读者，如果想参与写书，可以注册为作者
     * param    writename2  string  笔名
     * param    qq2    int  qq号码
     */
    public function _userRegisterForAuthor_myd(){
        $output     = array('status' => 0, 'message' => '', 'url' => '');
        $uid    = isLogin();
        $authorname = I('post.writename2', '', 'trim');
        $qq         = I('post.qq2', 0, 'intval');
        $phone      = I('post.phone2', 0, 'intval');
        $email      = I('post.mail2', '', 'trim');
        $fromuid    = I("post.fromuid", 0, "intval");
        if ( !$authorname || !$qq || !$phone || !$email) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        //一天内注册次数缓存key
        S(C('rdconfig'));
        $regcountkey = "regcount" . get_client_ip();
        $regcount    = S($regcountkey);
        if ($regcount > 3) {
            $output['message'] = '您注册的次数过于频繁';
            $this->ajaxReturn($output);
        }
        //$authorname
        $authornamelen = strLength($authorname);
        if ($authornamelen < 2 || $authornamelen > 8) {
            $output['message'] = '笔名格式错误';
            $this->ajaxReturn($output);
        }
        //手机
        if (!isValidPhone($phone)) {
            $output['message'] = '手机号错误';
            $this->ajaxReturn($output);
        }
        //email
        if (!isValidEmail($email)) {
            $output['message'] = '邮箱错误';
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        //检测笔名是否含有不恰当的字词
        $badwords  = $userModel->getAuthorNameBreakWords();
        $isbadword = $userModel->checkBreakword($badwords, $authorname);
        if ($isbadword) {
            $output['message'] = "您的笔名中含有违禁词";
            $output = array_merge($output, $isbadword);
            $this->ajaxReturn($output);
        }
        //笔名是否重复
        $authorexits = $userModel->checkAuthorNameExist($authorname);
        if ($authorexits) {
            $output['message'] = '笔名已存在';
            $this->ajaxReturn($output);
        }
        //分配责编
        if ($fromuid) {
            $zebianinfo = $userModel->getzeRenBianjibyUid($fromuid);
        }
        if (!$zebianinfo){
            $zebianinfo = $userModel->getRollzeBian('nv');
        }
        if (!$zebianinfo || !is_array($zebianinfo)) {
            $output['message'] = '注册失败，请稍后再试';
            $this->ajaxReturn($output);
        }
        //开始事物
        $userModel->startTrans();
        if ($uid) {
            $authorid = $userModel->addAuthor($uid, $authorname, $qq, $phone, $email, $zebianinfo['uid']);
            if ($authorid) {
                //提交事务,并设置登录状态
                $userModel->commit();
                //一天只能注册3次
                $now      = NOW_TIME;
                $tomorrow = strtotime(date('Y-m-d', strtotime('+1 day')));
                $expire   = $tomorrow - $now;
                if ($regcount) {
                    S($regcountkey, intval($regcount) + 1, $expire);
                } else {
                    S($regcountkey, 1, $expire);
                }
                $user     = $userModel->getAuthorByUid($uid);
                $isauthor = 0;
                if ($user && is_array($user)) {
                    $isauthor   = 1;
                    $authorid   = $user['authorid'];
                    $authorname = $user['authorname'];
                }
                $user['isauthor']   = $isauthor;
                $user['authorid']   = $authorid;
                $user['authorname'] = $authorname;
                $user['islogin']    = true;
                $user['uid']        = $uid;
                foreach ($user as $k => $vo) {
                    session($k, $vo);
                }
//                 session($user);
                $output['status']  = 1;
                $output['message'] = "恭喜，注册成功";
                $fu                = I("get.fu", "", "trim,removeXSS");
                if ($fu) {
                    $output['url'] = $fu;
                } else {
                    $output['url'] = url("User/authorLogin", array("sign" => 1), 'do');
                }
                $this->ajaxReturn($output);
            } else {
                $userModel->rollback();
                $output['message'] = '注册失败';
            }
        } else {
            $userModel->rollback();
            $output['message'] = '注册失败,请稍后再试';
        }
        $this->ajaxReturn($output);
    }

    /**
     * 触手注册
     */
    public function _register_chushou(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $username = I('post.username','','trim');
        $password = I('post.password','','trim');
        $repassword = I('post.repassword','','trim');
        $imgcode = I('post.yzm',0,'intval'); //验证码
        if(!$username || !$password || !$repassword || !$imgcode){
            $output['message'] = '注册信息不完整！';
            $this->ajaxReturn($output);
        }
        if($password!=$repassword) {
            $output['message'] = '两次密码不一致！';
            $this->ajaxReturn($output);
        }
        //验证验证码
        if (intval(session('imgcode')) != $imgcode) {
            $output['message'] = '验证码错误！';
            $this->ajaxReturn($output);
        }
        if(check_badword(cached_badword(false, 864000, 'username'), $username)){
            $output['message'] = '真实姓名不能含有违禁词！';
            $this->ajaxReturn($output);
        }
        $userModel = new \Client\Model\UserModel();
        //验证密码6-15位
        if(!preg_match("/^[\w~!@#%&*]{6,15}/", $password)){
            $output['message'] = '密码不符合和规范！';
            $this->ajaxReturn($output);
        }
        //验证用户名
        $usernamemsg = $userModel->we_check_username($username);
        if($usernamemsg != 'ok'){
            $output['message'] = $usernamemsg;
            $this->ajaxReturn($output);
        }
        $usernameexists = $userModel->checkUserNameExist($username);
        if(intval($usernameexists) > 0){
            $output['message'] = '用户名已重复！';
            $this->ajaxReturn($output);
        }
        C('TOKEN_ON', false);
        //插入数据库
        $model = M('User');
        $data = array();
        $data['username'] = $username;
        $data['nickname'] = $username;
        $data['password'] = $userModel->pwdEncrypt($password);
        $data['regip'] = get_client_ip();//获取客户端IP地址
        $data['groupid'] = 1;//默认分组号
        $data['regdate'] = time();//注册时间
        $data['credit'] = 2;
        $data['lastlogin'] = time();
        $uid = $model->add($data);
        if(intval($uid) > 0){
            $data['uid'] = $uid;
            M('ReadUser')->add($data);
            //设置登录状态
            $output = $userModel->loginByUsernamePassword($username,$password);
            $output['status'] = 1;
            $output['message'] = '恭喜您，注册成功！';
            $output['url'] = $this->M_forward;
        }else{
            $output['message'] = '注册失败，请重试'.$model->getError();
        }
        $this->ajaxReturn($output);
    }
}
