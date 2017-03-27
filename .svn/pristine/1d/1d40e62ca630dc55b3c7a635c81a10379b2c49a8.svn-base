<?php

namespace Client\Controller;

use Client\Common\Controller;
use Org\Wechatpay\WxPayApi;

class PayController extends Controller {
    public function _initialize() {
        parent::_initialize();
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '1.4.3'){
                doClient('User/login');
            }
            if (CLIENT_NAME == 'ios') {
                header("Location:" . url('User/login', array("fu" => url("Pay/index", array(), 'do')), 'do'));
                exit;
            } else {
                $this->redirect(url('User/login', array("fu" => url("Pay/index", array(), 'do')), 'do'), '', 2, '请先登录');
            }
        }
        //计算用户头像
        $user['userimg'] = getUserFaceUrl($uid, 'big');
        $this->assign('user', $user);
    }

    /**
     * 充值一级页面
     */
    public function _index() {
        $this->pageTitle = "充值";
        $this->assign('openid', '');
        //支付方式开关
        $allow_pay = array(
            'ALIPAY_WAP'=>array(
                    'title'=>'支付宝',
                    'logo'=>'ic_zhifubao2.png',
                    'third'=>'nowpay',
                    'payway'=>12
            ),
            'WEIXINPAY'=>array(
                    'title'=>'微信',
                    'third'=>'nowpay',
                    'logo'=>'ic_weixin.png',
                    'payway'=>13
            ),
            'WEIXINPAY_QRCODE'=>array(
                    'title'=>'微信扫码',
                    'third'=>'nowpay',
                    'logo'=>'ic_weixin.png',
                    'payway'=>53
            )
        );
        $pay_sort = array('ALIPAY_WAP', 'WEIXINPAY', 'WEIXINPAY_QRCODE');
        if (CLIENT_NAME === 'html5') {
            $is_wechat = isInWechat();
            if ($is_wechat) {
                $ologin = session('ologin');
                $cp = C('cache_prefix');
                C('cache_prefix', '');
                $key = 'wx_openid_'.session('uid');
                $M_redis = new \Think\Cache\Driver\Redis(C('rdconfig'));
                $openid = $M_redis->get($key);
                C('cache_prefix', $cp);
                if(!$openid) {
                    $openid = session('openid');
                }
                if ($openid && $ologin == 4) {
                    $this->assign('openid', $openid);
                }
                $pay_sort = array('WEIXINPAY', 'WEIXINPAY_QRCODE', 'ALIPAY_WAP');
            } else {
                //unset($allow_pay['WEIXINPAY']);
            }
            $this->assign('is_wechat', $is_wechat);
            //微信内,对某些渠道来源的用户,重新设定模版上的充值方式顺序
            $key = ':use_wechatqudao_set';
            $memcache = new \Think\Cache\Driver\Memcache();
            $use_wechatqudao_set = $memcache->get($key);

            $is_use_wechatqrcode = 0;
            if ($use_wechatqudao_set) {
                $tjusermodel = M('tj_user');
                $ids = implode(',', $use_wechatqudao_set);
                if (!empty($ids)) {
                    $is_use_wechatqrcode = $tjusermodel->field('user_id')->where('user_id=' . session('uid') . ' and link_id IN(' . $ids . ')')->find();

                    if ($is_use_wechatqrcode && is_array($is_use_wechatqrcode)) {
                        $is_use_wechatqrcode = 1;
                    } else {
                        $is_use_wechatqrcode = 0;       //注册渠道不符合条件
                    }
                }
                $pay_sort = array('WEIXINPAY_QRCODE', 'WEIXINPAY', 'ALIPAY_WAP');
            }
            $this->assign('is_use_wechatqrcode', $is_use_wechatqrcode);
        } else if (CLIENT_NAME === 'yqm') {
            if (strpos($_SERVER['HTTP_HOST'], 'hongshutest.com')) {
                _exit('对不起，测试站不支持在线支付，请<a href="http://q.hongshu.com" class="cred">点击这里</a>访问我们的正式站点。！<br />如有疑问，请<a href="http://q.hongshu.com/Help/Article/article_id/10.html" class="cred">联系客服</a>。');
            }
            //支持的充值方式
            $allow_pay = array(
                    'ALIPAY'
            );
        }
        $paylists = array();
        if($pay_sort) {
            foreach($pay_sort as $key) {
                if(isset($allow_pay[$key])) {
                    $paylists[$key] = $allow_pay[$key];
                    $paylists[$key]['scale'] = getPayConfig($key);
                }
            }
        }
        if(!$paylists) {
            $paylists = $allow_pay;
        }
        $this->assign('paylists', $paylists);

        $pay = 'ALIPAY';
        $money = '30';

        $this->assign('pay', $pay);                     //默认的充值方式
        $this->assign('money', $money);                 //默认的充值金额
        $this->assign('allow_pay', $allow_pay);         //支持的充值方式

        //支持的充值额度
        $allow_money = array(
            20, 30, 50, 100, 200, 500
        );
        if (session('priv')) {
            $allow_money[] = 1;
        }
        $this->assign('allow_money', $allow_money);     //支持的充值额度
        $siteid = getsiteconfig('fromsiteid');
        $this->assign('siteid', $siteid);
        $this->display('newpay');
    }

    /**
     * 充值一级页面
     */
    public function _index_ios() {
        $this->pageTitle = "充值";
        $ip = get_client_ip();
        $result = do_post_request('http://freeapi.ipip.net/' . $ip);
        $showAll = false;         //是否显示所有充值金额
        $yqd = true;
        if ($result && strpos($result, '中国')) {
            $yqd = false;
            $showAll = false;     //如果来源IP是中国，那么就不显示最新的充值金额
        }
        //是否在微信
        $isinwechat = isInWechat();
        //支持的充值额度
        $allow_money = array(
            18, 30, 50, 98, 198, 518
        );
        if (session('priv')) {
            $allow_money[] = 1;
        }
        $siteid = getsiteconfig('fromsiteid');
        $this->assign('siteid', $siteid);
        $this->assign("allow_money", $allow_money);
        $this->assign("isinwechat", $isinwechat);
        $this->assign('yqd', $yqd);
        $this->assign('showAll', $showAll);
        //$this->assign('ProductPrefix', (I('P27')=='1.3.4'?'YQKY':'YQK').'money');
        $this->display();
    }

    /**
     * 支付宝安卓SDK方式
     * @param string channel 安卓渠道名
     * @param int amount 充值金额
     */
    public function _AlipaySDK_android() {
        $amount = I('amount', 0, 'intval');
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            $this->ajaxReturn('请先登录');
        }
        if (!$amount) {
            $this->ajaxReturn('请选择要充值的金额！');
        }
        $channel = I('channel', '', 'trim,strtolower');
        $channel_id = 0;
        if ($channel) {
            $channel_set = C('channel_set');
            if ($channel_set) {
                $tmp = array_column($channel_set, 'channel_id', 'channel_name');
                if (isset($tmp[$channel])) {
                    $channel_id = $tmp[$channel];
                }
            }
        }

        $userinfo = M('ReadUser')->find($uid);
        if (!$userinfo) {
            $this->ajaxReturn('用户信息获取失败，请重新登录！');
        }
        $data = array(
            'buytime'    => time(),
            'buyid'      => $userinfo['uid'],
            'buyname'    => $userinfo['username'],
            'money'      => $amount,
            'payflag'    => 1,
            'paytype'    => 'ALIPAY',
            'channel_id' => $channel_id
        );
        $plModel = D('Paylogs');
        $out_trade_no = $plModel->addorder($data);
        if (!$out_trade_no) {
            $this->redirect(url("Pay/index", array(), 'do'), '', 2, '发生系统错误,请稍后再试！');
        } else {
            $alipay_config = C('alipayandroidsdk');
            if (!is_array($alipay_config) || !$alipay_config || !$alipay_config['partner']) {
                $this->redirect(url("Pay/index", array(), 'do'), '', 2, '发生系统错误,请稍后再试');
            }
            $amount = $amount . '.00';
            $signstring = 'service="mobile.securitypay.pay"&partner="' . $alipay_config['partner']
                . '"&_input_charset="utf-8"&return_url="' . $alipay_config['return_url'] . '"&notify_url="' . $alipay_config['notify_url'] . '"&out_trade_no="' . $out_trade_no
                . '"&subject="' . C('SITECONFIG.MONEY_NAME')
                . '"&body="' . C('SITECONFIG.MONEY_NAME') . '充值"&payment_type="1"&seller_id="' . $alipay_config['seller_email'] . '"&total_fee="' . $amount . '"';
            if (file_exists($alipay_config['private_key_path'])) {
                $priKey = file_get_contents($alipay_config['private_key_path']);
                $res = openssl_pkey_get_private($priKey);
            } else {
                $this->ajaxReturn('网络错误！');
            }
            //$sign       = '';
            openssl_sign($signstring, $sign, $res);
            openssl_free_key($res);
            //base64编码
            $sign = base64_encode($sign);
            // echo $sign;
            $result = array(
                'Action'       => 'payalipaysdk',
                'partner'      => $alipay_config['partner'],
                'seller_id'    => $alipay_config['seller_email'],
                'out_trade_no' => $out_trade_no,
                'subject'      => C('SITECONFIG.MONEY_NAME'),
                'body'         => C('SITECONFIG.MONEY_NAME') . '充值',
                'notify_url'   => $alipay_config['notify_url'],
                'total_fee'    => $amount,
                'return_url'   => $alipay_config['return_url'],
                'sign_type'    => 'RSA',
                'sign'         => $sign
            );
            if (IS_AJAX) {
                //$result = '{"Action":"payalipaysdk","partner":"' . $alipay_config['partner'] . '","seller_id":"' . $alipay_config['seller_email'] . '","out_trade_no":"' . $out_trade_no . '","subject":"' . C('SITECONFIG.MONEY_NAME') . '","body":"' . C('SITECONFIG.MONEY_NAME') . '充值","notify_url":"' . $alipay_config['notify_url'] . '","total_fee":"' . $amount . '","return_url":"' . $alipay_config['return_url'] . '","sign_type":"RSA","sign":"' . $sign . '"}';
                $cmd = '';
                foreach ($result as $key => $val) {
                    $cmd.='"' . $key . '":"' . $val . '",';
                }
                $cmd = '{' . substr($cmd, 0, -1) . '}';
                $this->ajaxReturn(array('status' => 1, 'command' => $cmd));
            } else {
                //unset($result['Action']);
                $cmd = array(
                    'partner'      => $alipay_config['partner'],
                    'seller_id'    => $alipay_config['saller_email'],
                    'out_trade_no' => $out_trade_no,
                    'subject'      => C('SITECONFIG.MONEY_NAME'),
                    'body'         => C('SITECONFIG.MONEY_NAME') . '充值',
                    'notify_url'   => $alipay_config['notify_url'],
                    'total_fee'    => $amount,
                    'return_url'   => $alipay_config['return_url'],
                    'sign_type'    => 'RSA',
                    'sign'         => $sign
                );
                doClient('payalipaysdk', $cmd);
            }
        }
    }

    /**
     * 微信安卓SDK方式
     * @param string channel 安卓渠道名
     * @param int amount 充值金额
     */
    public function _WeixinSDK_android() {
        $amount = I('amount', 0, 'intval');
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            $this->ajaxReturn('请先登录');
        }
        if (!$amount) {
            $this->ajaxReturn('请选择要充值的金额！');
        }
        $channel = I('channel', '', 'trim,strtolower');
        $channel_id = 0;
        if ($channel) {
            $channel_set = C('channel_set');
            if ($channel_set) {
                $tmp = array_column($channel_set, 'channel_id', 'channel_name');
                if (isset($tmp[$channel])) {
                    $channel_id = $tmp[$channel];
                }
            }
        }
        $userinfo = M('ReadUser')->find($uid);
        if (!$userinfo) {
            $this->ajaxReturn('用户信息获取失败，请重新登录！');
        }
        $data = array(
            'buytime'    => time(),
            'buyid'      => $userinfo['uid'],
            'buyname'    => $userinfo['username'],
            'money'      => $amount,
            'payflag'    => 1,
            'paytype'    => 'WEIXINPAY',
            'channel_id' => $channel_id
        );
        $plModel = D('Paylogs');
        $out_trade_no = $plModel->addorder($data);
        if (!$out_trade_no) {
            $this->redirect(url("Pay/index", array(), 'do'), '', 2, '发生系统错误,请稍后再试！');
        } else {
            $wechat_config = C('wechatandroidsdk');
            if (!is_array($wechat_config) || !$wechat_config || !$wechat_config['appid']) {
                $this->redirect(url("Pay/index", array(), 'do'), '', 2, '发生系统错误,请稍后再试');
            }

            $chpost = curl_init();

            $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
            $data = array(
                'appid'            => $wechat_config['appid'],
                'body'             => '即时购买' . C('SITECONFIG.MONEY_NAME'),
                'mch_id'           => $wechat_config['mch_id'],
                'nonce_str'        => WxPayApi::getNonceStr(),
                'notify_url'       => $wechat_config['notify_url'],
                'out_trade_no'     => $out_trade_no,
                'spbill_create_ip' => get_client_ip(),
                'total_fee'        => $amount * 100,
                'trade_type'       => "APP"
            );
            $data['sign'] = WxPayApi::MakeSign($data);

            $postdata = WxPayApi::ToXml($data);

            curl_setopt($chpost, CURLOPT_URL, $url);
            curl_setopt($chpost, CURLOPT_POST, 1);
            curl_setopt($chpost, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($chpost, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($chpost, CURLOPT_HEADER, 0);
            curl_setopt($chpost, CURLOPT_SSL_VERIFYPEER, false);
            $xml = curl_exec($chpost);
            curl_close($chpost);

            $ob = simplexml_load_string($xml, null, LIBXML_NOCDATA);
            $json = json_encode($ob);
            $Data = json_decode($json, true);

            $package = 'Sign=WXPay';
            $nonceStr = WxPayApi::getNonceStr();
            $timeStamp = time();
            $partnerid = $wechat_config['mch_id'];
            $prepayId = $Data['prepay_id'];

            $signarr = array();
            $signarr['appid'] = $wechat_config['appid'];
            $signarr['noncestr'] = $nonceStr;
            $signarr['package'] = $package;
            $signarr['partnerid'] = $partnerid;
            $signarr['prepayid'] = $prepayId;
            $signarr['timestamp'] = $timeStamp;

            $sign = WxPayApi::MakeSign($signarr);
            $result = array(
                'Action'    => 'paywechatsdk',
                'appid'     => $wechat_config['appid'],
                'partnerid' => $partnerid,
                'prepayId'  => $prepayId,
                'package'   => $package,
                'nonceStr'  => $nonceStr,
                'timeStamp' => $timeStamp,
                'sign'      => $sign
            );
            if (IS_AJAX) {
                $cmd = '';
                foreach ($result as $key => $val) {
                    $cmd.='"' . $key . '":"' . $val . '",';
                }
                $cmd = '{' . substr($cmd, 0, -1) . '}';
                $this->ajaxReturn(array('status' => 1, 'command' => $cmd));
            } else {
                unset($result['Action']);
                doClient($result);
            }
        }
    }

    /**
     * PC版支付接口
     *
     * @param int $payway 支付方式
     * @param int $amount 充值金额
     * @param string $yh 银行信息
     * @return int
     */
    public function _alipay_yqm() {
        if (strpos($_SERVER['HTTP_HOST'], 'hongshutest.com')) {
            _exit('对不起，测试站不支持在线支付，请<a href="http://q.hongshu.com" class="cred">点击这里</a>访问我们的正式站点。！<br />如有疑问，请<a href="http://q.hongshu.com/Help/Article/article_id/10.html" class="cred">联系客服</a>。');
        }
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            $this->redirect(url('User/login', array('fu' => 'Pay/index')), 2, '请登录！');
        }
        $payway = I("post.payway", "", "floatval");
        $amount = I("post.amount", "", "floatval");
        if (!is_numeric($payway)) {
            exit(0);
        }
        if (!is_numeric($amount)) {
            exit(0);
        }
        $usermodel = new \Client\Model\UserModel();
        $user = $usermodel->find($uid);
        if ($user['is_deleted'] == 1 || $user['is_locked'] == 1) {
            $usermodel->doLogout();
            echo "您的账户状态异常，不可进行充值。如有任何问题请联系客服。";
            exit(0);
        }
        unset($usermodel);
        // 支付宝
        $this->checkControl($payway);
        $paymodel = new \Client\Model\PaylogsModel();

        $data = array();
        $data["siteid"] = C('CLIENT.' . CLIENT_NAME . '.fromsiteid');
        $data["buytime"] = time();
        $data["buyid"] = $user["uid"];
        $data["buyname"] = $user["username"];
        $data["payflag"] = 1;
        $data["paytype"] = 'ALIPAY';
        $data["money"] = $amount;

        $tradeno = $paymodel->addorder($data);
        unset($paymodel);
        $aliapy_config = C('alipayconfig');

        $aliapy_config['return_url'] = ROOT_URL . '/Pay/alipayreturn.do';
        $aliapy_config['notify_url'] = ROOT_URL . '/Pay/alipaynotify.do';

        $out_trade_no = $tradeno;
        $subject = '即时付款购买' . C('SITECONFIG.MONEY_NAME');
        $body = $subject;
        $total_fee = $amount;

        $paymethod = '';
        $defaultbank = '';
        $anti_phishing_key = '';
        $exter_invoke_ip = '';
        $show_url = $aliapy_config['showurl'];
        $extra_common_param = '';
        $royalty_type = "";
        $royalty_parameters = "";

        $parameter = array(
            "service"            => "create_direct_pay_by_user",
            "payment_type"       => "1",
            "partner"            => $aliapy_config['partner'],
            "_input_charset"     => $aliapy_config['input_charset'],
            "seller_email"       => $aliapy_config['seller_email'],
            "return_url"         => $aliapy_config['return_url'],
            "notify_url"         => $aliapy_config['notify_url'],
            "out_trade_no"       => $out_trade_no,
            "subject"            => $subject,
            "body"               => $body,
            "total_fee"          => $total_fee,
            "paymethod"          => $paymethod,
            "defaultbank"        => $defaultbank,
            "anti_phishing_key"  => $anti_phishing_key,
            "exter_invoke_ip"    => $exter_invoke_ip,
            "show_url"           => $show_url,
            "extra_common_param" => $extra_common_param,
            "royalty_type"       => $royalty_type,
            "royalty_parameters" => $royalty_parameters
        );

        $alipayService = new \Org\Alipay\AlipayService($aliapy_config);
        $html_text = $alipayService->create_direct_pay_by_user($parameter);
        unset($alipayService);


//<form id='alipaysubmit' name='alipaysubmit' action='https://mapi.alipay.com/gateway.do?_input_charset=utf-8' method='get'><input type='hidden' name='_input_charset' value='utf-8'/><input type='hidden' name='body' value='即时付款购买元气币'/><input type='hidden' name='notify_url' value='http://q.hongshutest.com:8090Pay/alipay_notify.php'/><input type='hidden' name='out_trade_no' value='1165029'/><input type='hidden' name='partner' value='2088701562127742'/><input type='hidden' name='payment_type' value='1'/><input type='hidden' name='return_url' value='http://q.hongshutest.com:8090Pay/alipay_return.php'/><input type='hidden' name='seller_email' value='xulina@zhangyue.com'/><input type='hidden' name='service' value='create_direct_pay_by_user'/><input type='hidden' name='show_url' value='https://wallet.hongshu.com/profile/pay.html'/><input type='hidden' name='subject' value='即时付款购买元气币'/><input type='hidden' name='total_fee' value='20'/><input type='hidden' name='sign' value='d8d5560d19ca392fe39e1390119f50e4'/><input type='hidden' name='sign_type' value='MD5'/><input type='submit' value='确认'></form><script>document.forms['alipaysubmit'].submit();</script>

        echo $html_text;
    }

    public function _alipayreturn_yqm() {
        $order_no = I('get.out_trade_no', 0, 'intval');
        $total_fee = I('get.total_fee');           //获取总价格
        $trade_no = I('get.trade_no');      //支付宝交易号
        $trade_status = I('get.trade_status', '', 'trim,strtoupper');
        $data = array(
            'order_no'     => $order_no,
            'total_fee'    => $total_fee,
            'trade_no'     => $trade_no,
            'trade_status' => $trade_status
        );
        $result = $this->_alipay_process($data);
        if ($result < 1) {
            switch ($result) {
                case 0:
                    _exit('验证失败', 503);
                    break;
                case -1:
                    _exit('订单不存在', 503);           //空的订单号
                    break;
                case -2:
                    _exit('错误：指定的订单号' . $order_no . '不存在，请联系客服！', 503);
                    break;
                case -3:
                    _exit('错误：返回的金额不对，请联系客服！', 503);
                    break;
                case -4:
                    _exit('错误：没有与订单的支付方式对应的兑换比率，请联系客服', 503);
                    break;
                case -5:
                    _exit('错误：订单' . $order_no . '数据处理时出错，请联系客服', 503);
                    break;
                default :
                    _exit('错误：订单' . $order_no . '数据出错，请联系客服', 503);
                    break;
            }
        } else {
            $this->redirect(url('User/paylogs', '', 'do'), '', 2, '充值成功！');
        }
    }

    public function _alipaynotify_yqm() {
        $order_no = I('post.out_trade_no', 0, 'intval');
        $total_fee = I('post.total_fee');           //获取总价格
        $trade_no = I('post.trade_no');      //支付宝交易号
        $trade_status = I('post.trade_status', '', 'trim,strtoupper');
        $data = array(
            'order_no'     => $order_no,
            'total_fee'    => $total_fee,
            'trade_no'     => $trade_no,
            'trade_status' => $trade_status
        );
        $result = $this->_alipay_process($data);
        if ($result > 0) {
            exit('success');
        } else {
            exit('fail');
        }
    }

    private function _alipay_process($param) {
        \Think\Log::write(print_r(array('data' => $param), 1), 'ERROR', '', LOG_PATH . 'ALIPAYSDK_RETURN');
        $aliapy_config = C('alipayconfig');
        $aliapy_config['return_url'] = ROOT_URL . '/Pay/alipayreturn.do';
        $aliapy_config['notify_url'] = ROOT_URL . '/Pay/alipaynotify.do';
        if (IS_POST) {
            //计算得出通知验证结果
            $alipayNotify = new \Org\Alipay\AlipayNotify($aliapy_config);
            $verify_result = $alipayNotify->verifyNotify();
        } else {
            $verify_result = true;
        }
        if ($verify_result) {//验证成功
            $order_no = $param['order_no'];
            if (!$order_no) {
                return -1;
            }
            $total_fee = $param['total_fee'];           //获取总价格
            $trade_no = $param['trade_no'];      //支付宝交易号
            $trade_status = $param['trade_status'];
            if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                $paymodel = new \Client\Model\PaylogsModel();
                $payloginfo = $paymodel->getorder($order_no);
                if (!is_array($payloginfo)) {
                    \Think\Log::write(print_r(array('data' => $param, 'msg' => '错误：指定的订单号不存在，请联系客服'), 1), 'ERROR', '', LOG_PATH . 'ALIPAYSDK_RETURN');
                    return -2;
                }
                if ($payloginfo['payflag'] == 1) {
                    if (number_format($total_fee, 2) != number_format($payloginfo['money'], 2)) {//返回金额不对
                        $data = array(
                            'fromid'   => 0,
                            'fromname' => '手机支付宝接口',
                            'toid'     => $payloginfo['buyid'],
                            'toname'   => $payloginfo['buyname'],
                            'chglog'   => '支付宝自动返回出错,原因:金额错误,支付宝流水号:' . $trade_no . ',订单:' . $order_no . ',原金额:' . $payloginfo ['money'] . ',支付宝返回金额:' . $total_fee,
                            'siteid'   => 7
                        );
                        M('Systemlogs')->token(false)->add($data);
                        \Think\Log::write(print_r(array('data' => $param, 'msg' => '错误：返回的金额不对，请联系客服'), 1), 'ERROR', '', LOG_PATH . 'ALIPAYSDK_RETURN');
                        return -3;
                    }
                    $duihuanbili = getPayConfig($payloginfo['paytype'], 'scale');
                    if (!$duihuanbili) {
                        \Think\Log::write(print_r(array('data' => $param, 'msg' => '错误：没有与订单的支付方式对应的兑换比率，请联系客服'), 1), 'ERROR', '', LOG_PATH . 'ALIPAYSDK_RETURN');
                        return -4;
                    }
                    $addxdmoney = $total_fee * $duihuanbili;
                    $usermodel = M("user");
                    $map["uid"] = $payloginfo['buyid'];
                    $result = $usermodel->where($map)->setInc("money", $addxdmoney);
                    if (!$result) {//没有添加虚拟币
                        $msg = $userobj->getError();
                        if ($msg) {
                            $msg = ',错误原因：' . $msg;
                        }
                        $data = array(
                            'fromid'   => 0,
                            'fromname' => '手机支付宝接口',
                            'toid'     => $payloginfo['buyid'],
                            'toname'   => $payloginfo['buyname'],
                            'chglog'   => '支付宝自动返回添加' . C('SITECONFIG.MONEY_NAME') . '出错,支付宝流水号:' . $trade_no . ',订单:' . $order_no . ',原金额:' . $payloginfo ['money'] . ',支付宝返回金额:' . $total_fee . ',应增加' . C('SITECONFIG.MONEY_NAME') . $addxdmoney .
                            $msg,
                            'siteid'   => 7,
                        );
                        M('Systemlogs')->token(false)->add($data);
                        return -5;
                    }
                    //更新订单信息
                    $data = array(
                        'payflag' => 2,
                        'note'    => $trade_no,
                        'rettime' => time(),
                        'egold'   => $addxdmoney
                    );
                    $paymodel->addorder($data, $order_no);

                    //写入操作日志
                    $data = array(
                        'fromid'   => 0,
                        'fromname' => '支付宝接口',
                        'toid'     => $payloginfo['buyid'],
                        'toname'   => $payloginfo['buyname'],
                        'chglog'   => '自动增加' . C('SITECONFIG.MONEY_NAME') . $addxdmoney . '个（订单ID:' . $order_no . '）',
                        'siteid'   => 7,
                    );
                    M('Systemlogs')->token(false)->add($data);
                    return 1;
                } else {
                    return 1;
                }
            }
            return 1;
        } else {
            //验证失败
            return 0;
        }
    }

    public function weChatAction(){
        $this->pageTitle = "充值";
        $this->assign('openid', '');
            $is_wechat = isInWechat();
            if ($is_wechat) {

                $ologin = session('ologin');
                $key = 'txtxiaoshuowx_openid_'.session('uid');
                $M_redis = new \Think\Cache\Driver\Redis(C('rdconfig'));
                $openid = $M_redis->get($key);
                if(!$openid) {
                    $openid = session('openid');
                }
                if ($openid && $ologin == 4) {
                    $this->assign('openid', $openid);
                }
            }

            $this->assign('is_wechat', $is_wechat);

            //微信内,对某些渠道来源的用户,重新设定模版上的充值方式顺序
            $key = ':use_wechatqudao_set';
            $memcache = new \Think\Cache\Driver\Memcache();
            $use_wechatqudao_set = $memcache->get($key);

            //$use_wechatqudao_set = C('use_wechatqudao_set');


            $is_use_wechatqrcode = 0;
            if ($use_wechatqudao_set) {
                $tjusermodel = M('tj_user');
                $ids = implode(',', $use_wechatqudao_set);
                if (!empty($ids)) {
                    $is_use_wechatqrcode = $tjusermodel->field('user_id')->where('user_id=' . session('uid') . ' and link_id IN(' . $ids . ')')->find();

                    if ($is_use_wechatqrcode && is_array($is_use_wechatqrcode)) {
                        $is_use_wechatqrcode = 1;
                    } else {
                        $is_use_wechatqrcode = 0;       //注册渠道不符合条件
                    }
                }
            }
            $this->assign('is_use_wechatqrcode', $is_use_wechatqrcode);
        //支持的充值额度
        $allow_money = array(
            20, 30, 50, 100, 200, 500
        );
        if (session('priv')) {
            $allow_money[] = 1;
        }
        $this->assign('allow_money', $allow_money);     //支持的充值额度
        $siteid = getsiteconfig('fromsiteid');
        $this->assign('siteid', $siteid);
        $this->display();
    }
}
