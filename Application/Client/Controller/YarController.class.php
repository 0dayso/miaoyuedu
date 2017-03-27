<?php
namespace Client\Controller;

use Client\Common\Controller;

class YarController extends Controller {
    public function _initialize() {
        parent::_initialize();
        C('RPCURL', 'http://interface.hongshu.com/newyar');
        $this->check_user_login();
        $uid = session('uid');
        if(!$uid || !session('priv')) {
            _exit();
        }
    }

    /**
     * 接口列表
     */
    public function indexAction() {
        $this->pageTitle = '接口列表';
        $client = new \HS\Yar('index');
        $result = $client->apiLists();//
        $this->assign('apis', $result);
        $this->display();
    }

    /**
     * 接口测试
     */
    public function apiTestAction() {
        $this->pageTitle = '接口测试';
        $yar = I('yar', '', 'trim');
        $func = I('func', '', 'trim');
        if (!$yar || !$func) {
            $this->ajaxReturn('参数错误！');
        }
        $this->assign('yar', $yar);
        $this->assign('func', $func);
        $client = new \HS\Yar('index');
        $result = $client->apiDetail($yar, $func);//call_user_func_array(array($client, 'apiDetail'), array('api' => $yar, 'method' => $func));
        $params = $result['params'];
        if (!$params || IS_POST || IS_AJAX) {
            $_client = new \HS\Yar($yar);
            if (!$params) {
                $param = array();
            } else {
                $param = I('post.param');
                foreach ($params as $key => $set) {
                    if ($set['required'] && (!isset($param[$key]) || empty($param[$key]))) {
                        $this->error($set['title'] . '必须设置！');
                    }
                    if ($set['type'] == 'array') {
                        $param[$key] = json_decode(str_replace(array('&quot;', '&amp;'), array('"', '&'), $param[$key]), true);
                    }
                }
            }
            $returns = call_user_func_array(array($_client, $func), $param);
            $this->assign('returns', $returns);
            $this->assign('data', $param);
            //与老接口对比测试结果
//            C('RPCURL', 'http://interface.yanqingkong.com/yar');
//            $_client = new \HS\Yar($yar);
//            $old_returns = call_user_func_array(array($_client, $func), $param);
//            if (!$_client->getError()) {
//                pre($param);
//                pre($_client);
//                pre($old_returns);
//                $this->assign('old_returns', $old_returns);
//                if ($old_returns != $returns) {
//                    $this->assign('error', '与老接口返回值不同，请检查结果！');
//                }
//            } else {
//                pre($_client);
//                pre($old_returns);
//            }
        }
        $this->assign($result);
        $this->assign('service', $yar . '->' . $func);
        $this->display();
    }

    /**
     * 接口详情
     */
    public function apiDetailAction() {
        $this->pageTitle = '接口详情';
        $yar = I('yar', '', 'trim');
        $func = I('func', '', 'trim');
        if (!$yar || !$func) {
            $this->ajaxReturn('参数错误！');
        }
        $client = new \HS\Yar('index');
        if (IS_POST || IS_AJAX) {

        } else {
            $result = $client->apiDetail($yar, $func);//call_user_func_array(array($client, 'apiDetail'), array('api' => $yar, 'method' => $func));
            if(!$result) {
                pre($client);
            }
            $this->assign($result);
            $this->assign('service', $yar . '->' . $func);
            $this->assign('yar', $yar);
            $this->assign('func', $func);
            $this->display();
        }
    }


}
