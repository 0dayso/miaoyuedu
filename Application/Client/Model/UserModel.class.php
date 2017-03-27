<?php
/**
 * 模块: 客户端
 *
 * 功能: 用户相关
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: dingzi
 * @version: $Id: UserModel.class.php 1582 2017-03-20 12:28:25Z guonong $
 */
namespace Client\Model;
use \HS\Model;
use Org\Util\String;
class UserModel extends Model {
    /**
     * 获得用户id或书id(当天,昨天,上周....)的收藏,鲜花,砸砖次数
     *
     * @param unknown_type $uid
     * @param unknown_type $optimename=thisday|lastday|thisweek|lastweek|thismonth|lastmonth
     * @param unknown_type $optype
     * @return unknown
     */
    function getUserVoteCount($uid, $bid, $optimename = 'thisday', $optype = 'flower', $comtime = true) {
        $start_time = 0;
        $end_time = 0;
        $now_time = NOW_TIME;
        $whereAry = array();
        if ($comtime) {
            mk_time_xiangdui($now_time, $optimename, $start_time, $end_time);
            if ($start_time != 0 && $end_time != 0) {
                //$wherearray[] = "optime>={$start_time} AND optime<={$end_time}";
                $whereAry['optime'] = array("egt", $start_time);
                $whereAry['optime'] = array("elt", $end_time);
            } else {
                return false;
            }
        }
        if (!empty($uid)) {
            $whereAry['uid'] = $uid;
        }
        if (!empty($bid)) {
            $whereAry['bid'] = $bid;
        }
        switch ($optype) {
            case 'flower':
                $kind = '1';
                break;
            case 'zhuan':
                $kind = '2';
                break;
            case 'fav':
                $kind = '3';
                break;
            case 'star':
                $kind = '4';
                break;
            case 'urgemore':
                $kind = '5';
                break;
            case 'booksurvey':
                $kind = '6';
                break;
            case 'zhengwen':
                $kind = '7';
                break;
        }
        $ret = M("votelogs")->where($whereAry)->sum($kind);
        return $ret;
    }

    /**
     * 获得用于保存用户redis缓存的key名
     * @param unknown_type $uid
     *
     * @return string
     */
    function getUserRedisKey($uid) {
        return C('cache_prefix') . ':userinfo:' . $uid;
    }

    /**
     * 生成authcode
     * @param unknown_type $row
     */
    function generate_authcode($row, $uuid, $expiry = 31556952) {
        if (CLIENT_NAME === 'ios' || CLIENT_NAME === 'android') {
            if (CLIENT_VERSION >= '2.0.0') {
                $_authCode = uc_authcode($row['uid'] . "\t" . md5($row['password']) . "\t" . md5($uuid), 'ENCODE', C('CLIENT.' . CLIENT_NAME . '.passportkey'), $expiry);
            } else {
                $_authCode = uc_authcode($row['uid'] . "\t" . md5($row['password']), 'ENCODE', C('CLIENT.' . CLIENT_NAME . '.passportkey'), $expiry);
            }
			$_authCode = str_replace(' ', '+', $_authCode);
			cookie('authcookie', $_authCode);
        } else {
            $_authCode = uc_authcode($row['uid'] . "\t" . $row['username'] . "\t" . md5($row['password']), 'ENCODE', C('CLIENT.' . CLIENT_NAME . '.passportkey'), $expiry);
        }
        return $_authCode;
    }

    function decode_authcode($_authCode, $uuid) {
        $_authCode = str_replace(' ', '+', $_authCode);
        $tmp = uc_authcode($_authCode, 'DECODE', C('CLIENT.' . CLIENT_NAME . '.passportkey'));
        //android.ios检测uuid
        if((strtolower(CLIENT_NAME) === 'ios' || strtolower(CLIENT_NAME) === 'android') && CLIENT_VERSION >= '2.0.0'){
            $tmparr = explode("\t",$tmp);
            if(is_array($tmparr)){
                if(array_pop($tmparr) != md5($uuid)){
                    return array();
                }
            }else{
               return array();
            }
        }
        return explode("\t", $tmp);
    }

    /**
     * 登录,cookietime<=0,则不修改authcookie
     *
     * @param unknown_type $userid
     * @param unknown_type $cooktime
     * @return unknown
     */
    function login($userid, $cooktime, $uuid= '') {
        if(is_array($userid)) {
            $row = $userid;
            $userid = $row['uid'];
        } else {
            $row = $this->find($userid);
        }
        if (!is_array($row)) {
            $this->error = '用户不存在';
            return false;
        }
        if ($row['is_deleted'] || $row['is_locked']) {
            $this->error = '对不起，你的账号已经被封禁！请与客服联系！';
            return false;
        }
        $this->token('false');

        $upData = array(
            'lastlogin' => NOW_TIME, //最后登录时间
            'loginnum'  => array('exp', 'loginnum+1')       //登录次数加1
        );
        //送积分
        if (( date("j", $row['lastlogin']) != date("j", NOW_TIME) ) || ( date("n", $row['lastlogin']) != date("n", NOW_TIME) )) {
            $credit = intval(C("logincredits"));
            if ($credit > 0) {
                $this->setRedisData($userid, array('credit', $row['credit'] + $credit));
                $upData['credit'] = array('exp', 'credit+' . $credit);
            }
        }
        //更新一些信息
        $this->where("uid='" . $userid . "'")->data($upData)->save();
        if($uuid){
            $cookvar = $this->generate_authcode($row,$uuid);
        }else{
            $cookvar = $this->generate_authcode($row);
        }

        if ($cooktime > 0) {
            cookie('authcookie', $cookvar, $cooktime);
        }
        cookie('nickname', $row['nickname'], 31536000);
        cookie('uid', $userid, 31536000);

        $USERGROUP = C('USERGROUP');
        $_GROUP = $USERGROUP[$row['groupid']];

        $vipusers = C('USERVIP');

        if ($row['viplevel'] && $vipusers[$row['viplevel']]['name']) {
            $row['viplevelname'] = $vipusers[$row['viplevel']]['name'];
        } else {
            $row['viplevelname'] = '非VIP用户';
        }
        session('[start]');
        //登录记录只有在session中没有数据时记录一下
        if(!session('islogin')) {
            //登录记录
            $this->addLoginLog($userid);
        }
        session('islogin', true);
        if(is_null($row['nickname'])) {
            //数据库中居然有NULL的昵称
            $row['nickname'] = $row['username'];
        }
        //需要在session中记录的项目
        $data = array('priv', 'username', 'nickname', 'email', 'uid', 'viplevel', 'money', 'egold', 'viplevelname', 'groupid', 'postnum', 'credit', 'lastlogin', 'hongshudou', 'mobile', 'loginnum', 'ologin', 'openid');
        foreach ($data as $k) {
            session($k, $row[$k]);
        }
        $avatar = getUserFaceUrl($row['uid']);
        session('avatar',$avatar);
        session('groupname', $_GROUP['title']);
        $authormap = array(
            "uid"=>$row['uid'],
        );
        $authorinfo = M('Author')->where($authormap)->find();
        if (is_array($authorinfo)) {
            session('isauthor', 1);
            session('authorid', $authorinfo['authorid']);
            session('authorname', $authorinfo['authorname']);
        } else {
            session('isauthor', 0);
        }
        return true;
    }

    /**
     * 退出
     */
    public function doLogout() {
        cookie('authcookie', null);
        cookie('nickname', null);
        cookie('PHPSESSID', null);
        cookie('uid', null);
        cookie(session_id(), null);
        cookie(session_name(), null);
        session('islogin', false);
        session('uid', 0);
        $_SESSION = array();
        session(null, null);
        session_unset();
        session_destroy();
        session_write_close();
        return true;
    }

    /**
     * 检测注册用户名(客户端)
     * @param $username 用户名
     * @param $callajax 是否AJAX接口
     */
    function we_check_username($username, $callajax = false) {
        $username = trim($username);
        $is_pass = false;
        $msg = "";
        $ajaxmsg = "";
        if ($username == "" || strlen($username) <= 0) {
            $msg = "错误:用户名不能空";
            $ajaxmsg = "<font color=red>X</font>错误:用户名不能空";
        } elseif (preg_match('/(\s|　)+/i', $username)) {
            $msg = "错误:用户名不能有空格";
            $ajaxmsg = "<font color=red>X</font>错误:用户名前后不能有空格";
        } elseif (strlen($username) > 15) {
            $msg = "错误:用户名过长";
            $ajaxmsg = "<font color=red>X</font>错误:用户名过长";
        } elseif (strlen($username) < 2) {
            $msg = "错误:用户名过短";
            $ajaxmsg = "<font color=red>X</font>错误:用户名过短";
        } elseif (preg_match("/\s+|[%,\*\"\'\s\<\>\&]/is", $username)) {
            $msg = "错误:用户名含有非法字符";
            $ajaxmsg = "<font color=red>X</font>错误:用户名含有非法字符";
        } elseif (check_badword(cached_badword(false, 864000, 'username'), $username)) {
            $msg = "错误:用户名含有非法字符";
            $ajaxmsg = "<font color=red>X</font>错误:用户名含有非法字符";
        } elseif (CLIENT_NAME == 'myd' && preg_match('/[\x{4e00}-\x{9fa5}]/u', $username)){
            $msg = '错误：用户名含有中文';
            $ajaxmsg = "<font color=red>X</font>错误:用户名含有中文";
        } else {
            $is_pass = true;
        }
        if ($is_pass) {
            return "ok";
        } else {
            return $callajax ? $ajaxmsg : $msg;
        }
    }

    /**
     * 客户端自动注册检测密码
     *
     * @param string $password
     * @param string $repassword
     * @return string
     */
    function we_check_password($password, $repassword) {
        $password = trim($password);
        $repassword = trim($repassword);
        $is_pass = false;
        $msg = "";
        $ajaxmsg = "";
        if ($password == "" || strlen($password) <= 0) {
            $msg = "错误:密码不能空";
        } elseif (strlen($password) > 12) {
            $msg = "错误:密码过长";
        } elseif (strlen($password) < 4) {
            $msg = "错误:密码过短";
        } elseif ($password != stripslashes($password)) {
            $msg = "错误:密码含有非法字符";
        } elseif ($password != $repassword) {
            $msg = "错误:两次输入的密码不一样";
        } else {
            $is_pass = true;
        }
        if ($is_pass) {
            return "ok";
        } else {
            return $msg;
        }
    }

    /**
     * 检测注册邮箱
     * @param string $email
     * @param unknown $callajax
     * @return string
     */
    function we_check_email($email, $callajax) {
        $email = trim($email);
        $is_pass = false;
        $msg = "";
        $ajaxmsg = "";
        $mailmap = array("email"=>$email);
        $reademail = M("read_user")->where($mailmap)->select();
        if ($email == "" || strlen($email) <= 0) {
            $msg = "错误:邮件地址不能空";
            $ajaxmsg = "<font color=red>X</font>错误:邮件地址不能空";
        } elseif (preg_match('/(\s|　)+/i', $email)) {
            $msg = "错误:邮件地址不能有空格";
            $ajaxmsg = "<font color=red>X</font>错误:邮件地址不能有空格";
        } elseif (is_array($reademail) && $reademail) {
            $msg = "错误:已经有人使用该EMAIL注册过帐户";
            $ajaxmsg = "<font color=red>X</font>错误:已经有人使用该EMAIL注册过帐户";
        } elseif (!isValidEmail($email)) {
            $msg = "错误:邮件地址格式不对";
            $ajaxmsg = "<font color=red>X</font>错误:邮件地址格式不对";
        } else {
            $is_pass = true;
        }
        if ($is_pass) {
            return "ok";
        } else {
            return $callajax ? $ajaxmsg : $msg;
        }
    }

    /**
     * 增加用户money，并更新vip等级
     *
     * @param $uid 用户id
     * @param $money 增加的数量
     *
     * @return false/id
     */
    public function addMoney($uid, $money) {
        if (!$uid || !$money) {
            $this->error = '参数错误！';
            return false;
        }
        $userinfo = $this->find($uid);
        if (!$userinfo) {
            $this->error = '用户信息不存在！';
            return false;
        }
        $upviplevel = false;
        $setData = array();
        if (!$userinfo['viplevel']) {
            $setData['viplevel'] = 1;
            $upviplevel = 1;
        }
        if ($money >= 2000 && $userinfo['viplevel'] <= 1) {
            $setData['viplevel'] = 2;
            $upviplevel = 2;
        }
        if ($upviplevel) {
            if (session('uid') == $uid) {
                session('viplevel', $upviplevel);
            }
        }
        $setData['money'] = $userinfo['money'] + $money;
        $setData['uid'] = $uid;
        $ret = false;
        $ret = $this->data($setData)->save();
        if ($ret) {
            //数据库更新成功，处理一下缓存
            $redis = new \Think\Cache\Driver\Redis();
            $redis->sethash("user_normal#" . $uid, $setData);
            return $ret;
        } else {
            return false;
        }
    }

    /*     * *
     * 用户登录次数 +1
     * @param $uid
     */
    public function addLoginCount($uid) {
        $map["uid"] = $uid;
        //M("User")->where($map)->setInc("loginnum");   //和下面的操作合并为一步
        $loginnum = session("loginnum");
        $loginnum++;
        $lastlogin = NOW_TIME;
        $data = array(
            'lastlogin' => NOW_TIME,
            'loginnum'  => array('exp', 'loginnum+1')
        );
        $this->where($map)->data($data)->save();
        session("loginnum", $loginnum);
        session("lastlogin", $lastlogin);
    }

    /**
     * 用户获取登录积分
     * @param $uid
     * @param $credit
     */
    public function addCredit($uid, $credit) {
        $map["uid"] = $uid;
        $lastlogintime = M("user")->where($map)->getField("lastlogin");
        $today = strtotime(date('Y-m-d'));
        $nextday = strtotime(date('Y-m-d', strtotime('+1 day')));
        if (!($lastlogintime >= $today && $today < $nextday)) {
            M("User")->where($map)->setInc("credit", $credit);
            session('credit', session('credit') + $credit);
        }
    }

    /**
     * 增加银币,自动升级vip等级
     * @param unknown_type $uid
     * @param unknown_type $egold
     * @return boolean|unknown
     */
    public function addEgold($userInfo, $egold) {
        $data = array();
        $upviplevel = false;
        if (!$userInfo['viplevel']) {
            $data['viplevel'] = 1;
            $upviplevel = 1;
        }
        if ($egold >= 2000 && $userInfo['viplevel'] <= 1) {
            $data['viplevel'] = 2;
            $upviplevel = 2;
        }
        if (isset($userInfo['egold'])) {
            $data['egold'] = $userInfo['egold'] + $egold;
        } else {
            $data['egold'] = array('exp', 'egold+' . $egold);
        }
        $map = array('uid' => $userInfo['uid']);
        $ret = false;
        $ret = $this->where($map)->data($data)->save();
        if ($ret) {
            $redis = new \Think\Cache\Driver\Redis();
            $_ui = $redis->gethash('user_normal#' . $userInfo['uid'], array('viplevel', 'egold'));
            if ($upviplevel) {
                if (session('uid') == $userInfo['uid']) {
                    session('viplevel', $upviplevel);
                    $_ui['viplevel'] = $upviplevel;
                }
            }
            $_ui['egold']+=$egold;
            $redis->sethash("user_normal#" . $userInfo['uid'], $_ui);
        }
        return $ret;
    }

    /**
     * 消费
     *
     * @param int 用户id
     * @param int 消费金额,优先扣除egold
     * @return array
     */
    public function consume($uid, $money) {
        $arr["code"] = 0;
        $arr["deductmoney"] = 0;
        $arr["deductegold"] = 0;
        $arr["lastmoney"] = 0;
        $arr["lastegold"] = 0;
        $usermodel = M("user");
        $userInfo = $usermodel->find($uid);

//         $client = new \Yar_Client(C('RPCURL') . "/usermoney.php");
        $client = new \HS\Yar("usermoney");
        $result = $client->subUserMoney($uid, 'egoldfirst', $money);
        if (!$result || ($result['egold'] == 0 && $result['money'] == 0)) {
            $arr['code'] = -113;
        } else {
            $arr['code'] = 107;
            $arr['deductegold'] = $result['egold'];
            $arr['deductmoney'] = $result['money'];
            $arr['lastmoney'] = $userInfo['money'] - $result['money'];
            $arr['lastegold'] = $userInfo['egold'] - $result['egold'];
            //处理缓存！
            $redis = new \Think\Cache\Driver\Redis();
            $data = array(
                'money' => $arr['lastmoney'],
                'egold' => $arr['lastegold']
            );
            $redis->sethash("user_normal#" . $userInfo['uid'], $data);
        }
        unset($usermodel);
        return $arr;
    }

    /**
     * 用户登录统一接口，接受的参数为用户名和密码
     *
     * @param string $username
     * @param string $password
     * @param int $remeberMe
     * @param int $uType 0：用户名，1：用户ID，2：email，3：手机号
     * @param int $encEd 密码校验方式：0：使用内置的pwdEncrypt，1：与数据库中的密码的md5值进行比较,2:直接与数据库中的密码对比（元气萌用密码摘要登录）
     * @return array|false
     *           成功：返回数组，数组内容参见 XXX客户端服务器接口说明的1.16 用户登陆(注册)后通知客户端保存P30(用户校验码)部分，关键字：saveP30
     *           失败：返回False，错误信息由UserModel->getError()获取。
     */
    public function loginByUsernamePassword($username = '', $password = '', $rememberMe = 0, $uType = 0,$encType = 0,$uuid = '') {
        if(is_array($username)) {
            $userinfo = $username;
        } else {
            $username = trim($username);
            if (!$username) {
                $this->error = '账号不能为空！';
                return false;
            }
            $readUserModel = M("read_user");
            if ($uType == 1) {
                $uid = $username;
            } else {
                $map = array();
                if ($uType == 2) {
                    $map['email'] = $username;
                } else if ($uType == 3) {
                    $map['mobile'] = (string)$username;
                } else {
                    $map['username'] = $username;
                }
                $tmpinfo = $readUserModel->where($map)->find();
                if (!is_array($tmpinfo) || count($tmpinfo) < 1) {
                    $this->error = "用户名不能为空";
                    return false;
                }
                $uid = $tmpinfo['uid'];
            }
            $userinfo = $this->find($uid);
            if (!$userinfo || !is_array($userinfo)) {
                //TODO 实际的用户表里没找到这条记录，那么，wis_read_user中应该删除对应的记录
                if (!$this->getError()) {
                    //不是数据库查询错误
                    $this->error = '帐号不存在！';
                }
                return false;
            }
            //检查密码
            if ($encType == 1) {
                $pwdMatch = ($password == md5($userinfo['password']));
            } elseif($encType == 2){
                $pwdMatch = ($password == $userinfo['password']);
            } else {
                $pwdMatch = ($this->pwdEncrypt($password) == $userinfo['password']);
            }
            if (!$pwdMatch) {
                $this->error = '对不起，你的密码不正确。';
                return false;
            }
        }
        if ($userinfo['is_deleted'] == 1 || $userinfo['is_locked'] == 1) {
            $this->error = '对不起，你的账号已经被封禁！';
            return false;
        }
        if (!empty($userinfo['mobile'])) {
            $bounry_num = 100;
            if (CLIENT_NAME === 'ios' || CLIENT_NAME === 'android') {
                $tbName = CLIENT_NAME . '_bounry';
                $firstLogin = M($tbName)->find($userinfo['uid']);
                if (!$firstLogin) {
                    $data = array(
                        'uid'     => $userinfo['uid'],
                        'addtime' => NOW_TIME,
                        'egold'   => $bounry_num
                    );
                    $bId = M($tbName)->add($data);
                    if ($bId) {
                        $addEgoldId = $this->addEgold($userinfo, $bounry_num);
                        if (!$addEgoldId) {
                            //失败则写日志,wis_systemlogs
                            $sysData = array(
                                'fromid'      => 0,
                                'fromname'    => CLIENT_NAME . '初次登录送银币',
                                'toid'        => $userinfo['uid'],
                                'toname'      => $userinfo['username'],
                                'chglog'      => CLIENT_NAME . '初次登录送银币' . $bounry_num . '时发生错误，赠送失败',
                                'runprograme' => $_SERVER['PHP_SELF'],
                            );
                            $sysid = M("systemlogs")->add($sysData);
                        }
                    }
                }
            }
        }
        if (CLIENT_NAME === 'ios' || CLIENT_NAME === 'android') {
            $cookietime = 365 * 24 * 3600;
        } else {
            $cookietime = $rememberMe > 0 ? 365 * 24 * 3600 : 0;
        }
        if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0'){
            $islogin = $this->login($userinfo, 365 * 24 * 3600,$uuid);
        }else{
            $islogin = $this->login($userinfo, 365 * 24 * 3600);
        }
        $userinfo['isauthor'] = session('isauthor');

        session("islogin", $islogin);
        //计算authcode
        if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0'){
            $authcode = $this->generate_authcode($userinfo, $uuid);
        }else{
            $authcode = $this->generate_authcode($userinfo);
        }

        //返回值
        $data = array();
        $data['status'] = 1;
        $data['message'] = "登录成功";
        $data['nickname'] = $userinfo['nickname'];
        $data['usercode'] = $authcode;
        $data['username'] = $userinfo['username'];
        $data['uid'] = $userinfo['uid'];
        $data['viplevel'] = $userinfo['viplevel'];
        $data['groupid'] = $userinfo['groupid'];
        $data['groupname'] = C("USERGROUP")[$userinfo['groupid']]['title'];
        $data['money'] = $userinfo['money'];
        $data['isauthor'] = $userinfo['isauthor'];
        $data['avatar'] = getUserFaceUrl($userinfo['uid']);
        $data['remark'] = $this->setRemark($userinfo['password']);
        return $data;
    }

    private function setRedisData($uid, $data = array()) {
        if ($data) {
            $redis = new \Think\Cache\Driver\Redis();
            $redis->sethash("user_normal#" . $uid, $data);
        }
    }
    /**
     * 根据用户名获得用户信息数组(wis_user表)
     * @param string $username
     * @return array
     */
    public function getUser($username, $field = ""){
        $map['username'] = $username;
        $user = M('user')->where($map)->field($field)->find();
        if($user && is_array($user)) {
            return $user;
        }
        $map2['email'] = $username;
        $user = M('user')->where($map2)->field($field)->find();
        if($user && is_array($user)) {
            return $user;
        }
        $map3['mobile'] = (string)$username;
        $user = M('user')->where($map3)->field($field)->find();
        if($user && is_array($user)) {
            return $user;
        }
        return null;
    }

    /**
     * 获得用户实时金币
     *
     * @param int 用户id
     * @return array
     */
    public function getUserMoney($uid) {
        $map["uid"] = $uid;
        $hsCoin = M("user")->where($map)->getField("uid,money,egold");
        return $hsCoin;
    }

    /**
     * 获取用户的昵称
     *
     * @param int $uid 用户id
     * @return string
     */
    public function getUserNickname($uid){
        $map["uid"] = $uid;
        $nickname = M("ReadUser")->where($map)->getField("nickname");
        return $nickname;
    }

    /**
     * 获取某用户对某本书投的红票数
     *
     * @param int $uid 用户id
     * @param int $bid 书号
     * @return int
     */
    public function getUserTicketByBid($uid, $bid) {
        $map["zuo_bi"] = 0;
        $map["type"] = "01";
        $map["user_id"] = $uid;
        $map["bk_id"] = $bid;
        $red_ticket_ballot_model = M("red_ticket_ballot");
        $ret = $red_ticket_ballot_model->where($map)->count();
        unset($red_ticket_ballot_model);

        $proticket = 0;
        $map["uid"] = $uid;
        $map["bid"] = $bid;
        $book_pro_model = M("book_pro");
        $arr = $book_pro_model->where($map)->field("num, pid")->order("price desc")->select();
        $bookmodel = new \Client\Model\BookModel();
        for ($i=0; $i < count($arr); $i++) {
            $proinfo = $bookmodel->getProInfo($arr[$i]["pid"]);
            $proticket += $proinfo["ticket"] * $arr[$i]["num"];
        }
        unset($bookmodel);
        unset($book_pro_model);
        return ($ret + $proticket);
    }

    /**
     * 获取某用户对某本书投的价值最高的道具
     *
     * @param int $uid 用户id
     * @param int $bid 书号
     * @return int
     */
    public function getMaxPro($uid, $bid) {
        $map["uid"] = $uid;
        $map["bid"] = $bid;
        $book_pro_model = M("book_pro");
        $arr = $book_pro_model->where($map)->field("num, pid")->order("price desc")->limit(1)->select();
        $result = $arr;
        $map["pid"] = $arr[0]["pid"];
        $sumnum = $book_pro_model->where($map)->sum("num");
        $result[0]["num"] = $sumnum;
        unset($book_pro_model);
        return $result;
    }


    /**
     * 根据用户id获取作者信息
     *
     * @param int $uid 用户id
     * @return array
     */
    public function getAuthorByUid($uid) {
        $map['a.uid'] = $uid;
        $authormodel = M("author");
        $ret = $authormodel->alias('a')->join(" left join wis_author_file b on a.authorid = b.authorid")->where($map)->field("a.authorid, a.authorname, b.name, b.id_card")->find();
        unset($authormodel);
        return $ret;
    }
    /***
     * 判断用户名是否已被注册
     * @param $username
     * @return int 1 存在 其他 不存在
     * TODO 大于0：存在，等于0：不存在，小于0：参数错误
     */
    public function checkUserNameExist($username) {
        $ip = get_client_ip();
        if ($ip != '113.31.87.5' && $ip != '113.31.87.6') {
            $key = '_checkusernameexists_' . $ip;
            $result = S($key);
            if (!$result) {
                S($key, array('count' => 1, 'last' => NOW_TIME));
            } else {
                if ((NOW_TIME - $result['last']) > 60) {
                    $result = array(
                        'count' => 1,
                        'last'  => NOW_TIME
                    );
                } else {
                    $result['count'] ++;
                }
                S($key, $result);
                if ($result['count'] > 10) {
                    \Think\Log::write(print_r(array(
                        'HOST'       => I('server.HTTP_HOST')? : I('server.SERVER_NAME'),
                        'MODULE'     => MODULE_NAME,
                        'CONTROLLER' => CONTROLLER_NAME,
                        'ACTION'     => ACTION_NAME,
                        'username'   => $username . '(' . strlen($username) . ')',
                        'param'      => I('param.')
                        ), 1), 'DENNIED', '', LOG_PATH . '_DENNIED');
                    send_http_status(500);
                    die();
                }
            }
        }
        $usermodel = M('read_user');
        $map['username'] = $username;
        if (isValidEmail($username)) {
            $map['email'] = $username;
        }

        if (isValidPhone($username)) {
            $map['mobile'] = $username;
        }
        $map['nickname'] = $username;
        $map['_logic'] = 'OR';
        $ret = $usermodel->where($map)->count();
        unset($usermodel);
        if($ret) {
            return $ret;
        }
        return 0;
    }
    /***
     * 判断昵称是否已被注册
     * @param $nickname
     * @return int 1 存在 其他 不存在
     */
    public function checkNickNameExist($nickname) {
        //昵称为空，返回存在！
        if (!trim($nickname)) {
            return 1;
        }
        $map['nickname'] = $nickname;
        $ret = M('read_user')->where($map)->count();
        return $ret;
    }

    /***
     * 判断邮件是否已被注册
     * @param $email
     * @return int 1存在 2不存在
     */
    public function checkEmailExist($email){
        $map['email'] = $email;
        $ret = M('read_user')->where($map)->count();
        return $ret;
    }
    /***
     * 判断手机是否已被注册
     * @param $mobile
     * @return int 1存在 2不存在
     */
    public function checkPhoneExist($phone){
        $map['mobile'] = (string)$phone;
        $ret = M('read_user')->where($map)->count();
        return $ret;
    }
    /***
     * 密码加密函数
     * @param string $str 需要加密的字符串
     * @return string
     */
    public function pwdEncrypt($str) {
        $str=base64_encode($str);
        $str=str_replace("=","",$str);
        $str=substr($str,1,strlen($str)-1).substr($str,0,1);
        $str=md5($str);
        return $str;
    }
    /***
     * 判断昵称是否已被注册
     * @param $authorname
     * @return int 1 存在 其他 不存在
     */
    public function checkAuthorNameExist($authorname){
        $map['authorname'] = $authorname;
        $ret = M('author')->where($map)->count();
        if($ret) {
            return $ret;
        }
        return 0;
    }

    /***
     * 获取违禁词
     * @return string
     */
    function getAuthorNameBreakWords() {
        parent::initMemcache();
        $breakwords = S("breakword_content");
        return $breakwords;
    }

    /***
     * 检查内容中是否包含违禁词
     * @param $breakword 违禁词
     * @param $content 内容
     * @return bool
     */
    function checkBreakword($breakword, $content){
        $matches=false;
        if(strlen($breakword) > 0){
            if(preg_match_all('/'.$breakword.'/is', $content, $matches)){
                return $matches[0];
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /***
     *说明：用户注册同时写入多张表，只对wis_user表做数据库操作判断。
     * 新用户注册（填写邮件）
     * @param string $username
     * @param string $password
     * @param string $email
     * @return $userId
     */
    public function add($username, $password, $email, $phone) {
        $username = trim($username);
        if (!$username) {
            $username = $this->makeUsername();
        }
        $data['username'] = $username;
        $data['nickname'] = $username;
        $data['password'] = $this->pwdEncrypt($password);
        if($email) {
            $data['email'] = $email;
        }
        if ($phone) {
            $data['mobile'] = $phone;
        }

        $data['regip'] = get_client_ip();//获取客户端IP地址
        $data['groupid'] = 1;//默认分组号
        $data['regdate'] = time();//注册时间
        $data['credit'] = 2;
        $data['lastlogin'] = time();
        $uid = M('user')->add($data);

        if($uid){//记录注册UID的当前程序和域名
            $dataLog['uid'] = $uid;
            $dataLog['phpself'] = $_SERVER['PHP_SELF'];
            $dataLog['httphost'] = $_SERVER['HTTP_HOST'];
            M('registerlogs')->add($dataLog);

            $dataBookshelf['category_name'] = '默认书架';
            $dataBookshelf['uid'] = $uid;
            $dataBookshelf['remark'] = '默认书架分类,不能改名删除';
            M('bookshelf_category')->add($dataBookshelf);

            $dataReadUser['uid'] = $uid;
            $dataReadUser['username'] = $username;
            $dataReadUser['nickname'] = $username;
            if($email) {
                $data['email'] = $email;
                $dataReadUser['email'] = $email;
            }
            if ($phone) {
                $data['mobile'] = $phone;
                $dataReadUser['mobile'] = $phone;
            }
            M('read_user')->add($dataReadUser);
            //渠道
            $client_cookie_prefix = C('COOKIE_PREFIX');
            C('COOKIE_PREFIX',C('CHANNEL_COOKIE_PREFIX'));
            $_from_sid = cookie('_from_sid');
            if($_from_sid){
                $this->addTjUser($_from_sid, $uid);
            }
            //还原cookie前缀
            C('COOKIE_PREFIX',$client_cookie_prefix);
        }
        return $uid;
    }

    /**
     * 根据用户id获取用户信息数组
     * @param int $uid 用户id
     * @param string $field 所需字段
     * @return array
     */
    public function getUserbyUid($uid, $field){
        $map['uid'] = $uid;
        $user = M('user')->where($map)->field($field)->find();
        return $user;
    }

    /**
     * 添加登录日志
     * @param int $uid 用户id
     */
    public function addLoginLog($uid) {
        $data["uid"] = $uid;
        $data["host"] = $_SERVER['HTTP_HOST'];
        $data["logintime"] = time();
        $data["ip"] = get_client_ip();
        $data["sid"] = session_id();
        $data["useagent"] = $_SERVER['HTTP_USER_AGENT']?:"UNKNOW";
        //M("userloginlog")->add($data);
        $client = new \HS\Yar('userlog');
        $client->saveLoginLog($data);
    }

    /**
     * 添加作者
     * @param int $uid 用户id
     * @param string $authorname 作者名
     * @param string $qq qq
     * @param string $phone 手机号
     * @param string $email 电子邮件
     * @param int $bianjiid 编辑id
     * @return int
     */
    public function addAuthor($uid, $authorname, $qq, $phone, $email,$bianjiid) {
        $data['uid'] = $uid;
        $data['authorname'] = $authorname;
        $data['qq'] = $qq;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['regdate'] = time();
        $data['ispizhun'] = 0;

        $data['bianjiid'] = $bianjiid;
        $ret = M("author")->add($data);
        return $ret;
    }
    /**
     * 根据uid获得一个责编
     * @param unknown_type $uid
     */
    public function getzeRenBianjibyUid($uid){
         $map['uid'] = $uid;
         return M('admin_privileges')->where($map)->find();
    }
    /**
     * 获得一个循环分配的责编info
     * @param unknown_type $sex_flag
     */
    public function getRollzeBian($sex_flag){
        $redis = new \HS\MemcacheRedis();
        $key = 'regauthor:lastfrombjorder:'.$sex_flag;//上次是哪个order
        $lastorder = $redis->getRedis($key);

        $map['sex_flag'] = ($sex_flag=='nan')?'nan':'nv';
        $map['is_allot'] = 1;

        $roll_bj_arys = M('admin_privileges')->where($map)->select();
        $count = count($roll_bj_arys)-1;
        //print_r($lastorder);
        if(!$lastorder || $lastorder>$count){
            $lastorder = 0;
        }


        $redis->setRedis($key, $lastorder+1);
        return $roll_bj_arys[$lastorder];
    }

    /**
     * 根据uid判断是否作者
     * @param int $uid 用户id
     * @return int
     */
    public function checkAuthorByUid($uid) {
        $map['uid'] = $uid;
        $ret = M("author")->where($map)->count();
        return $ret;
    }

    /**
     * 根据email获取用户信息
     * @param int $email 电子邮件
     * @return array
     */
    public function getUserByEmail($email) {
        $map['email'] = $email;
        $ret = M("read_user")->where($map)->find();
        return $ret;
    }

    /**
     * 设置密码
     * @param int $uid 用户id
     * @param string $password 密码
     * @return int
     */
    public function setPassword($uid, $password) {
        $map['uid'] = $uid;
        $data = array();
        $data['password'] = $this->pwdEncrypt($password);
        $ret = $this->where($map)->token(false)->save($data);
        if ($ret === false) {
            if ($this->getError()) {
                $ret = false;
            } else {
                $ret = true;
            }
        }
        if($ret) {
            //修改密码以后需要把所有此帐号的登录给踢下线
            $memcachedObj = new \Memcache();
            $memcachedObj->addserver(C('mcconfig_ses.host'), C('mcconfig_ses.port'), false);
            $m = M('Userloginlog');
            $lists = $m->where($map)->select();
            if($lists) {
                foreach($lists as $row) {
                    if($key=$row['sid']) {
                        $memcachedObj->delete($key);
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * 根据手机号获得用户信息
     * @param string $phone 手机号
     * @return array
     */
    public function getUserByPhone($phone) {
        $map['mobile'] = (string)$phone;
        $ret = M("read_user")->where($map)->find();
        return $ret;
    }

    /**
     * 获取某个用户红票余额
     *
     * @param int 用户id
     * @return int
     */
    public function getLastTicket($uid, $month) {
        if(!$month){
            $month = date("ym");
        }
        $map["user_id"] = $uid;
        $map["month"] = $month;
        $num = M("red_ticket_user")->where($map)->getField("num");
        if(!$num){
            $num = 0;
        }
        return $num;
    }

    /**
     * 获取当日/月某个用户送红票的次数
     *
     * @param int 用户id
     * @param int 书号
     * @param int 0:日 1:月
     * @return int
     */
    function getSendTCount($uid, $bid, $type){
        if($type == 0){
            $map["i_date"] = date("Y-m-d");
        }
        elseif($type == 1){
            $map["month"] = date("ym");
        }
        $map["type"] = "01";
        $map["bk_id"] = $bid;
        $map["user_id"] = $uid;
        $num = M("red_ticket_ballot")->where($map)->sum("num_true");
        if(!$num){
            $num = 0;
        }
        return $num;
    }

    /**
     * 查询用户的订单
     * @param int $uid 用户id
     * @param int $field 所需字段
     * @param string $limit 查询多少条
     * @return array
     */
    public function getPaylog($uid, $field, $limit) {
        $map['buyid'] = $uid;
        $map['payflag']  = array('in','2,3');
        $paylog = M('paylogs')->where($map)->field($field)->order("buytime desc")->limit($limit)->select();
        return $paylog;
    }

    /**
     * 根据uid获取订购信息
     * @param int $uid 用户id
     * @return array
     */
    public function getAllSaleinfo($uid) {
//         $client = new \Yar_Client(C('RPCURL')."/dingoujson.php");
        $client = new \HS\Yar("dingoujson");
        $result = $client->getAllSaleinfo($uid);
        unset($client);
        return $result;
    }

    /**
     * 升级vip
     * @param int $uid 用户id
     * @param int $viplevel vip等级
     * @param int $price 升级花费的金币金额
     * @return int
     */
    public function levelUp($uid, $viplevel, $price) {
        $map['uid'] = $uid;
        $user = M("user");
        $user->where('uid='.$uid)->setField('viplevel', $viplevel);
        $ret = $user->where($map)->setDec('money', $price);
        unset($user);
        return $ret;
    }

    /**
     * 增加升级vip日志
     * @param int $uid 用户id
     * @param int $price 升级花费的金币金额
     * @param int $viplevel vip等级
     * @return int
     */
    public function addViplog($uid, $price, $viplevel) {
        $data["saletime"] = time();
        $data['uid'] = $uid;
        $data['memo'] = "消耗". $price . "个".C('SITECONFIG.MONEY_NAME');
        $data['viplevel'] = $viplevel;
        $viplogs = M("viplogs");
        $ret = $viplogs->add($data);
        unset($viplogs);
        return $ret;
    }

    /**
     * 修改个人设置
     * @param int $uid 用户id
     * @param string $nickname 昵称
     * @param string $mobile 手机号
     * @param string $email 电子邮件
     * @param string $password 密码
     * @param string $signature 个人签名
     * @param int $background 自定义背景
     * @return int
     */
    public function userSet($uid, $nickname, $mobile, $email, $password, $signature, $background) {
        $map['uid'] = $uid;
        $user = M("user");
        if ($nickname) {
            $data['nickname'] = $nickname;
            $data['nickallow'] = 1;
            $dataread['nickname'] = $nickname;
        }
        if ($mobile) {
            $data['mobile'] = $mobile;
            $dataread['mobile'] = $mobile;
        }
        if ($email) {
            $data['email'] = $email;
            $dataread['email'] = $email;
        }
        if ($password) {
            $data['password'] = $this->pwdEncrypt($password);
        }
        if ($signature) {
            $data['signature'] = $signature;
        }

        $data['background'] = $background;

        $ret = 0;
        if ($data && is_array($data)) {
            $ret = $user->where($map)->save($data);
        }
        unset($user);
        $readuser = M("read_user");
        if ($dataread && is_array($dataread)) {
            $readuser->where($map)->save($dataread);
        }
        unset($readuser);
        return $ret;
    }

    /***
     * 获取红薯卡分页数据
     * @param $uid
     * @param int $pageNum
     * @param int $pageSize
     * @return mixed
     */
    public function getHsCard($uid,$pageNum=1,$pageSize=15){
        $count = M('card')->where('uid ='.$uid)->count();
        $totalPage = ceil($count/$pageSize); //总页数
        $startNum = ($pageNum -1)*$pageSize;
        if($totalPage == $pageNum){//若是尾页
            $pageSize = $count - ($totalPage-1) * $pageSize;
        }
        $condition['uid'] = $uid;
        $data = M('card')->where($condition)->limit($startNum,$pageSize)->order('addmonth DESC')->select();
        $dataAry['result'] = $data;
        foreach($dataAry['result'] as $key => $value){
            $dataAry['result'][$key]['addmonth'] = date('Y-m-d H:i:s',$value['addmonth']);
        }
        $dataAry['totalPage'] = $totalPage;
        return $dataAry;
    }
    /***
     * 用户充值信息
     * @param $uid
     * @return array
     */
    public function getUserPayRecord($uid,$pageNum=1,$moneyType = 0,$depositType){
        $pageSize = 15;
        switch($depositType){
            case 'pay': //充值
                $paylogsModel = M('paylogs');
                $condition['buyid'] = $uid;
                $condition['moneytype'] = $moneyType;
                $count = $paylogsModel->where($condition)->count();
                $totalPage = ceil($count/$pageSize); //总页数
                $startNum = ($pageNum -1)*$pageSize;
                if($totalPage == $pageNum){//若是尾页
                    $pageSize = $count - ($totalPage-1) * $pageSize;
                }
                unset($condition);
                $whereAry['a.buyid'] = $uid;
                $whereAry['a.moneytype'] = $moneyType;
                $payInfoArray['result'] =  $paylogsModel->alias('a')->join("wis_payactivitylogs as b ON a.payid = b.payid","LEFT")->where($whereAry)->order('a.payid DESC')->limit($startNum,$pageSize)->field("a.paytype,a.buytime,a.egold,a.money,a.payid,a.payflag,b.paymoney,b.hongshumoney")->select();
                foreach($payInfoArray['result'] as $key => $value){
                    $payInfoArray['result'][$key]['paytype'] = getPayConfig($value['paytype'], 'name');
                    $payInfoArray['result'][$key]['buytime'] = date("Y-m-d H:i:s", $value['buytime']);
                    $payInfoArray['result'][$key]['egold'] = $value['egold'] . C('MONEY_NAME');
                    if($value['hongshumoney'] != ''){
                        $payInfoArray['result'][$key]['zengsong'] = 1;
                        $payInfoArray['result'][$key]['hongshumoney'] = $value['hongshumoney'] . C('MONEY_NAME');
                    }
                    if($value['payflag'] == 1){//支付失败，获取支付时间间隔失败
                        $overTime  = time() - $value['buytime'];
                        if($overTime > 86400){
                            $payInfoArray['result'][$key]['failtype'] = 1;
                        }else{
                            $payInfoArray['result'][$key]['failtype'] = 2;
                        }
                    }
                }
                break;
            case 'registration': //手机签到
                if($moneyType == 0){
                    $totalPage = 0;
                    break;
                }
                $tempArray = M()->query("SELECT count(egold) as count FROM(
                                         SELECT egold FROM wis_android_qiandao WHERE uid = {$uid}
                                         UNION ALL
                                         SELECT egold FROM wis_ios_qiandao WHERE uid = {$uid}) t ");

                $count = $tempArray[0]['count'];
                $totalPage = ceil($count/$pageSize); //总页数
                $startNum = ($pageNum -1)*$pageSize;
                if($totalPage == $pageNum){//若是尾页
                    $pageSize = $count - ($totalPage-1) * $pageSize;
                }
                $regsql = "SELECT * FROM(
                                         SELECT egold,linjiang_day,qiandao_day,concat('android') AS typestr FROM wis_android_qiandao WHERE uid = {$uid}
                                         UNION ALL
                                         SELECT egold,linjiang_day,qiandao_day,concat('ios') AS typestr FROM wis_ios_qiandao WHERE uid = {$uid}) t  LIMIT {$startNum},{$pageSize}";
                $payInfoArray['result'] = M()->query($regsql);
                foreach($payInfoArray['result'] as $key => $value){
                    if($value['typestr']=='android'){
                        $payInfoArray['result'][$key]['paytype'] = '安卓手机签到';
                    }
                    else{
                        $payInfoArray['result'][$key]['paytype'] = 'iOS手机签到';
                    }
                    if($value['linjiang_day']){
                        $payInfoArray['result'][$key]['payflag'] = 2;
                        $payInfoArray['result'][$key]['buytime'] = '20'.$value['linjiang_day'];
                    }
                    else{
                        $payInfoArray['result'][$key]['payflag'] = 1;
                        $payInfoArray['result'][$key]['failtype'] = 1;
                    }
                    $payInfoArray['result'][$key]['egold'] = $value['egold'].C('SITECONFIG.EMONEY_NAME');

                }
                break;
            case 'official': //官方赠送
                if($moneyType == 0){ //过滤金币
                    $officalCountSql = "SELECT COUNT(*) as count  FROM wis_payactivitylogs WHERE uid =  {$uid}";
                    //$tempArray = M()->query("SELECT COUNT(*) FROM wis_payactivitylogs WHERE uid =  {$uid}");
                }elseif($moneyType == 1){//过滤银币
                    $officalCountSql = "SELECT count(*) as count  FROM(
                                     SELECT egold FROM wis_android_bounry WHERE uid = {$uid}
                                     UNION ALL
                                     SELECT egold FROM wis_ios_bounry WHERE uid = {$uid}
                                     ) t";
                }else{
                    break;
                }
                $tempArray = M()->query($officalCountSql);
                $count = $tempArray[0]['count'];
                $totalPage = ceil($count/$pageSize); //总页数
                $startNum = ($pageNum -1)*$pageSize;
                if($totalPage == $pageNum){//若是尾页
                    $pageSize = $count - ($totalPage-1) * $pageSize;
                }
                if($moneyType == 0){//过滤金币
                    $officalSql = "SELECT payid,hongshumoney,addtime  FROM wis_payactivitylogs WHERE uid =  {$uid}";

                } elseif ($moneyType == 1) {//过滤银币
                    $officalSql = "SELECT *  FROM(
                                     SELECT egold,addtime  FROM wis_android_bounry WHERE uid = {$uid}
                                     UNION ALL
                                     SELECT egold,addtime  FROM wis_ios_bounry WHERE uid = {$uid}
                                     ) t LIMIT {$startNum},{$pageSize}";
                }else{
                    break;
                }
                $payInfoArray['result'] =  M()->query($officalSql);
                foreach($payInfoArray['result'] as $key => $value){
                    $payInfoArray['result'][$key]['paytype'] = '官方赠送';
                    $payInfoArray['result'][$key]['payflag'] = 2;
                    $payInfoArray['result'][$key]['buytime'] = date("Y-m-d H:i:s", $value['addtime']);
                    if($moneyType == 0){
                        $payInfoArray['result'][$key]['egold'] = $value['hongshumoney'] . C('MONEY_NAME');
                    }else{
                        $payInfoArray['result'][$key]['egold'] = $value['egold'] . C('EMONEY_NAME');
                    }
                }
                break;
            case 'hscard': //红薯卡
                if($moneyType == 0){
                    $totalPage = 0;
                    break;
                }elseif($moneyType == 1){

                    $cardModel = M("card");
                    $condition['uid'] = $uid;
                    $condition['cardtype'] = 1;
                    $condition['isusered'] = 1;
                    $count = $cardModel->where($condition)->count();
                    $totalPage = ceil($count/$pageSize); //总页数
                    $startNum = ($pageNum -1)*$pageSize;
                    if($totalPage == $pageNum){//若是尾页
                        $pageSize = $count - ($totalPage-1) * $pageSize;
                    }
                    $payInfoArray['result'] = $cardModel->where($condition)->field("cardnum")->limit($startNum,$pageSize)->select();
                    foreach($payInfoArray['result'] as $key => $value){
                        $payInfoArray['result'][$key]['paytype'] = '红薯卡';
                        $payInfoArray['result'][$key]['egold'] = $value['cardnum'];
                        $payInfoArray['result'][$key]['payflag'] = 2;
                    }
                    break;
                }else{
                    break;
                }
        }
        $payInfoArray['totalPage'] = $totalPage;
        return $payInfoArray;
    }

    /***
     * 用户充值统计
     * @param $uid
     * @return int
     */
    public function payRecordCount($uid){
        $sql ="SELECT COUNT(*) as count FROM wis_paylogs as a LEFT JOIN wis_payactivitylogs as b ON a.payid = b.payid WHERE a.buyid=".$uid;
        $data = M()->query($sql);
        return $data[0]['count'];
    }
    /**
     * 获得订阅分表名字,2012年10月(含10月)之前的不分表
     * @param unknown_type $yearmonth 例如:'1210'
     * @return string
     */
    public function getSalelogsTablename($yearmonth=''){
        if(!empty($yearmonth)){
            $tableSubname=(int)$yearmonth;
        }
        else {
            $tableSubname=(int)date("ym");
        }
        if($tableSubname<=1210){
            return 'salelogs';
        }
        return 'salelogs'.$tableSubname;
    }
    public function countBuyRecord($uid,$tableName){
        $newlogModel = M($tableName);
        $condition['uid'] = $uid;
        $countNum =  $newlogModel->where($condition)->count();
        unset($newlogModel);
        return $countNum;
    }
    //1210之前的订阅统计
    public function  countOldBuyRecord($uid,$month){
        $timeAry = $this->mFristAndLast(2012,$month);
        $condition['uid'] = $uid;
        $condition['saletime'] = array('BETWEEN',array($timeAry['firstday'],$timeAry['lastday']));
        $data = M('salelogs')->where($condition)->count();
        return $data;
    }

    /**
     * 根据卡号获得一个红薯卡相关信息
     * @param unknown_type $cardno
     * @return multitype:
     */
    public function getCardBycardno($cardno){
        $condition['cardno'] = $cardno;
        $cardModel = M("card");
        $dataArray = $cardModel->where($condition)->find();
        unset($cardModel);
        return $dataArray;
    }

    /**
     * 兑换一张红薯卡
     * @param unknown_type $uid
     * @param unknown_type $username
     * @param unknown_type $cardinfo
     * @param unknown_type $userobj
     * @return boolean|string
     */
    public function duihuanHsCard($userInfo,$cardInfo){
        switch($cardInfo['cardtype']){
            case 1://银币
                $isUsed = $this->updateCardInfo($cardInfo,$userInfo['uid']);
                if(!empty($isUsed)){
                    $this->addEgold($userInfo,$cardInfo['cardnum']);
                }
                return 1; //银币兑换成功
                break;
            case 2://红薯豆
                $isUsed = $this->updateCardInfo($cardInfo,$userInfo['uid']);
                if(!empty($isUsed)){
                    $userModel = M('user');
                    $condition['uid'] = $userInfo['uid'];
                    $userModel->where($condition)->setInc('hongshudou',$cardInfo['cardnum']);
                }
                return 2; //红薯豆兑换成功
                break;
            default:
                return false;
        }
    }
    /**
     * 修改一个红薯卡的状态
     * @param unknown_type $cardid
     * @param unknown_type $note
     * @param unknown_type $isusered
     * @return Ambigous <number, boolean>
     */
    public function updateCardInfo($cardInfo,$uid){
        $userIp = get_client_ip();
        $cardModel = M('card');
        $data['isusered'] = 1;
        $data['ip'] = $userIp;
        $data['uid'] = $uid;
        $data['gettime'] = time();
        $condition['id'] =  $cardInfo['id'];
        $ret= $cardModel->where($condition)->save($data);
        unset($cardModel);
        return $ret;
    }

    /**
     * 查询openid是否存在
     * @param int $ologin 类别
     * @param string $openid
     * @param string $unionid
     * @return int
     */
    public function getUserCountByOpenid($ologin, $openid, $unionid = '') {
        $usermodel = M("user");
        $map["oLogin"] = $ologin;
        $map["openid"] = $openid;
        $ret = $usermodel->where($map)->count();
        if(!$ret && $ologin==4 && $unionid!=''){
            //没有找到合适的openid，则查找unionid对应的openid
            $map = array(
                'unionid' => $unionid,
                'openid'=>array('neq', ''),
                'wid'=>0
            );
            $wuModel = D('WechatUsers');
            $result = $wuModel->where($map)->find();
            if($result){
                $map = array(
                    'oLogin' => $ologin,
                    'openid' => $result['openid']
                );
                $ret = $usermodel->where($map)->count();
            }
            unset($wuModel);
        }
        unset($usermodel);
        return $ret;
    }

    /**
     * 根据openid查询用户的信息
     * @param int $ologin 类别
     * @param string $openid
     * @param string $field 所需字段
     * @param string $unionid
     * @return array
     */
    public function getUserByOpenid($ologin, $openid, $field, $unionid = '') {
        $usermodel = M("user");
        $map["oLogin"] = $ologin;
        $map["openid"] = $openid;
        $arr = $usermodel->where($map)->field($field)->find();
        if(!$arr){
            if($ologin==4 && $unionid!=''){
                //没有找到合适的openid，则查找unionid对应的openid
                $map = array(
                    'unionid' => $unionid,
                    'openid'=>array('neq', ''),
                );
                $wuModel = D('WechatUsers');
                $result = $wuModel->where($map)->select();
                if($result){
                    $_openid = array_column($result, 'openid');
                    $map = array(
                        'oLogin' => $ologin
                    );
                    $tmp = array();
                    foreach($_openid as $v){
                        $tmp[] = " openid='{$v}' ";
                    }
                    $map[] = implode(' OR ', $tmp);
                    $arr = $usermodel->where($map)->field($field)->order('uid')->find();
                }
                unset($wuModel);
            }
        }
        unset($usermodel);
        return $arr;
    }

    /**
     * 递归生成用户名，用户第三方注册
     * @return string
     */
    public function makeUsername() {
        $username = C("SITECONFIG.AUTO_USERNAME_PREFIX") . \Org\Util\String::randString(9, 1);
        $ret = $this->checkUserNameExist($username);
        if ($ret == 1) {
            return $this->makeUsername();
        }
        else {
            return $username;
        }
    }

    /**
     * 生成昵称
     * @param int $ologin 类别
     * @param string $thirdnickname 用户在第三方第三方的昵称
     * @return string
     */
    public function makeNickname($ologin,$thirdnickname=NULL) {
        $source = "";
        if ($ologin == 1) {
            $source = "QQ用户";
        }
        else if ($ologin == 2) {
            $source = "新浪用户";
        }
        else if ($ologin == 3) {
            $source = "支付宝用户";
        }
        else if ($ologin == 4) {

            $source = "微信用户";
        }
        else if ($ologin == 5) {
            $source = "百度用户";
        }
        // 如果第三方昵称没有,则自动生成一个带前缀+数字的昵称
        if ($thirdnickname == NULL || !trim($thirdnickname)) {
            $nickname = $source . \Org\Util\String::randString(9, 1);
        }
        else{//否则按第三方昵称去检测是否重名
            $nickname = $thirdnickname;
        }
        $nickname = trim($nickname);
        $ret = $this->checkNickNameExist($nickname);
        if ($ret >= 1) {
            return $this->makeNickname($ologin);
        }
        else {
            return $nickname;
        }
    }

    /**
     * 更新用户的信息
     * @param array $data 用户信息的数组
     * @param int $uid 用户id
     * @return int
     */
    public function updateUser($data, $uid) {
        $usermodel = M("user");
        $usermap = array("uid"=>$uid);
        $ret = $usermodel->where($usermap)->save($data);
        if (isset($data['nickname'])) {
            $readuser = M("read_user");
            $info = array(
                'nickname' => $data['nickname']
            );
            $readuser->where('uid=' . $uid)->save($info);
        }
        unset($usermodel);
        return $ret;
    }

    public function checkUserFieldExist($field, $value) {
        $usermodel = M("user");
        $map[$field] = $value;
        $count = $usermodel->where($map)->count();
        unset($usermodel);
        return $count;
    }
    public function countUserPayNum($uid,$moneyType,$payType){
        switch($payType) {
            case 'pay': //充值
                $tempArray = M()->query("SELECT COUNT(*) as count FROM wis_paylogs as a LEFT JOIN wis_payactivitylogs as b ON a.payid = b.payid WHERE a.buyid={$uid} AND a.moneytype = {$moneyType}");
                return $tempArray[0]['count'];
                break;
            case 'registration':
                if($moneyType == 0){//金币
                    $tempArray[0]['count'] = 0;
                }elseif($moneyType == 1){//银币
                    $tempArray = M()->query("SELECT count(egold) as count FROM(
                                         SELECT egold FROM wis_android_qiandao WHERE uid = {$uid}
                                         UNION ALL
                                         SELECT egold FROM wis_ios_qiandao WHERE uid = {$uid}) t ");
                }else{
                    return false;
                }
                return $tempArray[0]['count'];

                break;
            case 'official':
                if($moneyType == 0){ //过滤金币
                    $officalCountSql = "SELECT COUNT(*) as count  FROM wis_payactivitylogs WHERE uid =  {$uid}";
                }elseif($moneyType == 1){//过滤银币
                    $officalCountSql = "SELECT count(*) as count  FROM(
                                     SELECT egold FROM wis_android_bounry WHERE uid = {$uid}
                                     UNION ALL
                                     SELECT egold FROM wis_ios_bounry WHERE uid = {$uid}
                                     ) t";
                }
                $tempArray = M()->query($officalCountSql);
                return $tempArray[0]['count'];
                break;
            case 'hscard':
                if($moneyType == 0){
                    $tempArray[0]['count'] = 0;
                }elseif($moneyType == 1){
                    $tempArray = M()->query("SELECT count(*) as count FROM wis_card WHERE uid={$uid} AND cardtype = 1 AND isusered = 1");
                }else{
                    return false;
                }
                return $tempArray[0]['count'];
                break;
            default:
                return false;
        }
    }
    //获取1210之前的消费记录
    public function getOldBuyRecordAry($uid,$moth,$pageNum){
        $pageSize = 15;
        $timeAry = $this->mFristAndLast(2012,$moth);
        $salelogsModel = M("salelogs");
        $condition['uid'] = $uid;
        $condition['saletime'] = array('between',array($timeAry['firstday'],$timeAry['lastday']));
        $totalNum = $salelogsModel->where($condition)->count();
        $totalPage = ceil($totalNum/$pageSize);
        $dataAry['totalPage'] = $totalPage;
        $startNum = ($pageNum -1)*$pageSize;
        if($totalPage == $pageNum){//若是尾页
            $pageSize = $totalNum - ($totalPage-1) * $pageSize;
        }
        unset($condition);
        $condition['log.uid'] = $uid;
        $condition['saletime'] = array('between',array($timeAry['firstday'],$timeAry['lastday']));

        $dataAry['result'] = $salelogsModel->alias('log')->join("wis_book ON wis_book.bid=log.bid",'LEFT')->where($condition)->order('log.saletime DESC')->limit($startNum,$pageSize)->field("log.*,wis_book.catename")->select();
        unset($salelogsModel);
        foreach($dataAry['result'] as $key => $value){
            $dataAry['result'][$key]['orderid'] = $key+1;
            $dataAry['result'][$key]['saletime'] = date("Y-m-d H:i:s", $value['saletime']);
            if($value['moneytype']==1){
                $dataAry['result'][$key]['saleprice']=$value['saleprice']."(".C('SITECONFIG.EMONEY_NAME').")";
            }else{
                $dataAry['result'][$key]['saleprice']=$value['saleprice']."(".C('SITECONFIG.MONEY_NAME').")";
            }
        }
        return $dataAry;
    }
    public function getUpLevelAry($uid,$year,$month,$pageNum){
        $pageSize = 15;
        $viplogsModel = M('viplogs');
        $timeAry = $this->mFristAndLast($year,$month);
        $condition['uid'] = $uid;
        $condition['saletime'] = array('between',array($timeAry['firstday'],$timeAry['lastday']));
        $totalNum = $viplogsModel->where($condition)->count();
        $totalPage = ceil($totalNum/$pageSize);
        $dataAry['totalPage'] = $totalPage;
        $startNum = ($pageNum -1)*$pageSize;
        if($totalPage == $pageNum){//若是尾页
            $pageSize = $totalNum - ($totalPage-1) * $pageSize;
        }
        $tmpAry = $viplogsModel->where($condition)->limit($startNum,$pageSize)->select();
        foreach($tmpAry as $key => $value){
            $dataAry['result'][$key]['orderid'] = $key+1;
            $dataAry['result'][$key]['saletime'] = date("Y-m-d H:i:s", $value['saletime']);
            if($value['memo'] =='消耗6000个'.C('SITECONFIG.MONEY_NAME')){
                $tmptitle = '初级VIP升高级VIP';
                $price = 6000;
            }elseif($value['memo'] =='消耗5000个'.C('SITECONFIG.MONEY_NAME')){
                $tmptitle = '中级VIP升高级VIP';
                $price = 5000;
            }elseif($value['memo'] =='消耗1000个'.C('SITECONFIG.MONEY_NAME')){
                $tmptitle = '初级VIP升中级VIP';
                $price = 1000;
            }

            $dataAry['result'][$key]['catename'] = $tmptitle;
            $dataAry['result'][$key]['saleprice'] = $price."(".C('SITECONFIG.MONEY_NAME').")";
        }
        return $dataAry;
    }

    //获取指定月份的时间戳范围
    public function mFristAndLast($y=2012,$m=""){
        $m=sprintf("%02d",intval($m));
        $y=str_pad(intval($y),4,"0",STR_PAD_RIGHT);

        $m>12||$m<1?$m=1:$m=$m;
        $firstday=strtotime($y.$m."01000000");
        $firstdaystr=date("Y-m-01",$firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
        return array("firstday"=>$firstday,"lastday"=>$lastday);
    }

    public function userGroupidUp($uid) {
        $usermodel = M("user");
        $levelmap['uid'] = $uid;
        $credit = $usermodel->where($levelmap)->field("credit")->find();

        $groups = C("USERGROUP");

        $nowgroupid = 0;
        for ($i = 1; $i <= count($groups); $i++) {
            if (intval($credit['credit']) >= intval($groups[$i]["higher"])
                && intval($credit['credit']) <= intval($groups[$i]["lower"])) {
                $nowgroupid = $groups[$i]["id"];
                break;
            }
        }
        $leveldata['groupid'] = $nowgroupid;
        $usermodel->where($levelmap)->save($leveldata);
        unset($usermodel);
        return $nowgroupid;
    }

    public function addTjUser($tjuser, $uid) {
        $tjusermodel = M("tj_user");
        $data["link_id"] = $tjuser;
        $data["user_id"] = $uid;
        $data["ip"] = get_client_ip();
        $data["i_time"] = date("Y-m-d H:i:s");
        $tjusermodel->add($data);
        unset($tjusermodel);
    }

    public function addTjSiteUser($tjSiteDomain, $uid) {
        $tjsiteusermodel = M("tj_site_user");
        $data["site_domain"] = $tjSiteDomain;
        $data["user_id"] = $uid;
        $data["ip"] = get_client_ip();
        $data["i_time"] = date("Y-m-d H:i:s");
        $tjsiteusermodel->add($data);
        unset($tjsiteusermodel);
    }

    /**
     * 给指定用户添加、扣除虚拟币
     * @param int $uid 指定的用户UID
     * @param int $money 要添加的数量（为0不动，负数为减少，正数为增加）
     * @return type
     */
    public function changeUserMoney($uid, $money) {
        $map["uid"] = $uid;
        if($money == 0 || $uid = 0) {
            return true;
        }
        $user_money = $this->where($map)->getField('money');
        if($user_money) {
            $money += $user_money;
            if($money<0) {
                $this->error = '指定用户的余额不足';
                return false;
            }
            return $this->token(false)->where($map)->setField('money', $money);
        } else {
            $this->error = '指定的用户未找到！';
            return false;
        }
    }
    /**
     * 生成密码摘要
     * @param  string $str 用户表中的密码
     * @param  int  $time microtime的小数部分
     */
    public function setRemark($str){
        $time = array_pop(explode('.',microtime(true)));
        $key = bin2hex($time);
        $encode = uc_authcode($str, 'ENCODE', $key);
        $remark = $encode.$key;
        return $remark;
    }
    /**
     * 验证密码摘要
     * @param String $remark 密码摘要
     * @param string $where 查询条件
     */
    public function checkRemark($remark,$where = array()){
        $authcode = substr($remark,0,82);
        $key = substr(trim($remark),82);
        $decode = uc_authcode($authcode,'DECODE',$key);
        if($decode && $where){
            $str = $this->where($where)->getField('password');
            if($str && $decode === $str){
                return $decode;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 检查权限
     *
     */
    public function checkPriv($needpriv,$userinfo){
        $priv = new \HS\PriModel();
        if($userinfo['priv']==''){
            return false;
        }
        $PRIV=$this->cached_priv();
        if(!isset($PRIV[$needpriv])){
            return false;
        }
        foreach ($PRIV[$needpriv] as $k=>$v){
            if($v && $priv->allowedPopedom($userinfo['priv'],$v)){
                return true;
            }
        }
        return false;
    }
    /**
     * 获取权限缓存，没有则从数据库获取
     */
    function cached_priv($flush=false){
        $cacheid_key='_privinfo';
        $cacheModel = new \HS\MemcacheRedis();
        $PRIV=$cacheModel->getRedis($cacheid_key);
        if($flush || !is_array($PRIV)){
            unset($PRIV);
            $cacheModel->delRedis('_PRIV');
            $popeModel = M('PopedomInfo');
            $tmparray= $popeModel->select();
            foreach ($tmparray as $k=>$v){
                $PRIV[$v['popedomname']][]=$v['popedomcode'];
            }
            $cacheModel->setRedis($cacheid_key,$PRIV);
        }
        return $PRIV;
    }
    /**
     * 设置电子邮件的找回密码的验证码，存入memcache
     *
     * @param int $uid 用户id
     * @param string $code 验证码
     */
    public function setPwdValidCode($uid, $code) {
        $cacheModel = new \HS\MemcacheRedis();
        $key = 'findpwdcode#'.$uid;
        $cacheModel->setMc($key, $code,1800);
    }
    /**
     * 获取邮件找回密码的验证码
     */
    public function getPwdValidCode($uid){
        $cacheModel = new \HS\MemcacheRedis();
        $key = 'findpwdcode#'.$uid;
        $code = $cacheModel->getMc($key);
        return $code;
    }
    /**
     * 查询后的处理，红薯居然有NULL的昵称，这里给处理一下
     * @param type $result
     * @param type $options
     */
    protected function _after_find(&$result, $options) {
        if(!$result['nickname']) {
            if(isset($result['username']) && $result['username']) {
                $result['nickname'] = $result['username'];
            } else {
                $result['nickname'] = randomstr(10);
            }
        }
    }
}
