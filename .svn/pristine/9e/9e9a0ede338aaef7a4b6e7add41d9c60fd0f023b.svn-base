<?php

namespace Client\Controller;

use Client\Common\Controller;

class PayajaxController extends Controller {

    /**
     * 获得一个新支付订单号(IOS)
     *
     * @param int $amount 数量
     *
     * @return array json数组
     */
    public function _getpayid() {

        //fromSite区分客户端
        if (CLIENT_NAME == 'ios') {
            $fromSite = C("CLIENT." . CLIENT_NAME . ".fromsiteid");
        } else {
            client_output_error("unknown client");
        }

        //获得订单号,返回json数据,成功则status=1
        $output = array('status' => 0, 'message' => '');
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            client_output_error(C('ERRORS.needlogin'));
        }

        $uModel = new \Client\Model\UserModel();
        $userinfo = $uModel->getUserbyUid($uid);
        if (!is_array($userinfo)) {
            client_output_error(C('ERRORS.needlogin'));
        }

        //产品ids,18, 30, 50, 98, 198, 518
        $productIds = array(
            C('SITECONFIG.APPLY_PAY_PREFIX')."money18"=>18,
            C('SITECONFIG.APPLY_PAY_PREFIX')."money30"=>30,
            C('SITECONFIG.APPLY_PAY_PREFIX')."money50"=>50,
            C('SITECONFIG.APPLY_PAY_PREFIX')."money98"=>98,
            C('SITECONFIG.APPLY_PAY_PREFIX')."money198"=>198,
            C('SITECONFIG.APPLY_PAY_PREFIX')."money518"=>518,
        );
        $allowAmountarys = array(18, 30, 50, 98, 198, 518);
        
        $amount = I('request.amount', 0, 'intval');
        $productId = I('request.productId','','trim');
        if ((empty($amount) || !in_array($amount, $allowAmountarys)) && (!$productId || !array_key_exists($productId, $productIds))) {
            $output['message'] = '请选择金额';
            $this->ajaxReturn($output);
        }
        
        if(!$amount || ($productId && array_key_exists($productId, $productIds))){
            $amount = $productIds[$productId];
        }

        $data = array(
            'siteid' => $fromSite,
            'buytime' => time(),
            'buyid' => $userinfo['uid'],
            'buyname' => $userinfo['username'],
            'money' => $amount,
            'payflag' => 1,
            'paytype' => 'APPLEPAY'
        );
        $paylogs = M('paylogs');
        $out_trade_no = $paylogs->data($data)->add();

        if ($out_trade_no === FALSE) {
            $output['message'] = '发生系统错误,请稍后再试';
        } else {
            $output['payid'] = $out_trade_no;
            $output['message'] = '成功!';
            $output['status'] = 1;
        }
        $this->ajaxReturn($output);
    }
    /**
     * 获取充值赠送设置
     * @param type $paytype
     * @param type $amount
     */
    public function getPayActivityAction() {
        $paytype = I('paytype', '', 'trim,strtoupper');
        if (!$paytype) {
            $this->ajaxReturn('参数错误');
        }
        $result = array(
            'message' => '',
            'zhekou' => getPayConfig($paytype, 'scale')
        );
        $today = date('Y-m-d H:i:s', NOW_TIME);
        $act   = C('PAYACTIVITY');
        if(!$act) {
            //有可能缓存中没有
            C(load_config(COMMON_PATH.'/Conf/payactivity.php'));
            $act   = C('PAYACTIVITY');
        }
        if(!$act) {
            $this->ajaxReturn('暂无活动');
        }
        if (isset($act[$paytype])) {
            $config = $act[$paytype];
            if ($config['is_activity']) {
                //有可用的活动
                if (($config['starttime']==0 || $today >= $config['starttime']) && ($config['endtime']==0 || $today <= $config['endtime'])) {
                    //活动已经开始
                    $result = array(
                        'start'  => $config['starttime'],
                        'end'    => $config['endtime'],
                        'give'   => $config['largessmoney'],
                        'status' => 1
                    );
                    $this->ajaxReturn($result);
                }
            }
        }
        $this->ajaxReturn('参数错误');
    }

}
