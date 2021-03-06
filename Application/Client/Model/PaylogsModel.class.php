<?php
/**
 * 模块: 三合一
 *
 * 功能: 充值记录数据表模型
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: PaylogsModel.class.php 1185 2016-09-19 03:24:42Z guonong $
 */

namespace Client\Model;

use HS\Model;

class PaylogsModel extends Model {
    /* 自动完成项目 */
    protected $_auto = array(
        array('rettime', NOW_TIME, self::MODEL_BOTH),
        array('siteid', 'getsiteconfig', self::MODEL_BOTH, 'function', 'fromsiteid'),
        array('chgtype', 0, self::MODEL_BOTH),
        array('runprograme', 'getThinkPara', self::MODEL_BOTH, 'function')
    );

    /**
     * 获取订单
     *
     * @param int $id 订单id
     * @param string $field 所需字段
     * @return array
     */
    public function getorder($id, $field = '*') {
        //订单的查询要从主库中取！！！！！但是对配置文件有个要求，就是第一个必须是主库！！！！！
        $dbconfig = array(
            'DB_TYPE'               =>  'mysql',
            'DB_HOST'               =>  current(explode(',', C('DB_HOST'))),
            'DB_NAME'               =>  current(explode(',', C('DB_NAME'))),
            'DB_USER'               =>  current(explode(',', C('DB_USER'))),
            'DB_PWD'                =>  current(explode(',', C('DB_PWD'))),
            'DB_PORT'               =>  current(explode(',', C('DB_PORT'))),
            'DB_PREFIX'             =>  'wis_',    // 数据库表前缀
        );
        $db = \Think\Db::getInstance($dbconfig);
        $result = $db->select(array('table'=>'wis_paylogs', 'where'=>array('payid'=>$id), 'field'=>$field));
        if($result && is_array($result)) {
            $result = $result[0];
        } else {
            $result = false;
        }
        unset($db);
        return $result;
    }

    /**
     * 增加/修改订单
     *
     * @param array $data 订单数组
     * @param int $id 订单id
     * @return int
     */
    public function addorder($data, $id = 0) {
        if(!isset($data['siteid']) || !$data['siteid']) {
            $data['siteid'] = C('CLIENT.'.CLIENT_NAME.'.fromsiteid');
        }
        if ($id == 0) {
            $id  = $ret = $this->add($data);
        } else {
            $ret = $this->where("payid = " . $id)->save($data);
        }
        if ($ret) {
            if ($data['payflag'] == 2 || $data['payflag'] == 3) {
                $this->payActivity($data);
            }
        }
        return $ret;
    }

    /**
     * 充值赠送活动检测
     * @param int|array $id
     * @return boolean
     */
    public function payActivity($id = 0) {
        if (!$id) {
            $this->error = '参数错误';
            return false;
        }
        if (is_array($id)) {
            if (!isset($id['payid'])) {
                $this->error = '参数错误';
                return false;
            }
            $data = $id;
            $id   = $data['payid'];
            if (!isset($data['payflag']) || !isset($data['paytype']) || !isset($data['money']) || !isset($data['rettime']) || !isset($data['buyid'])) {
                //数据不全，查一次数据库
                $data = $this->find($id);
            }
            if (!$data) {
                $this->error = '参数错误';
                return false;
            }
        }
        if ($data['payflag'] == 2 || $data['payflag'] == 3) {
            //充值成功，检查是否有活动
            $uid     = $data['buyid'];
            $today   = date('Y-m-d H:i:s', $data['rettime']);
            $amount  = intval($data['money']);
            $paytype = $data['paytype'];
            $act     = C('PAYACTIVITY');
            if (!$act) {
                //有可能缓存中没有
                C(load_config(APP_PATH . '/Client/Conf/payactivity.php'));
                $act = C('PAYACTIVITY');
            }
            if (!$act) {
                //没有活动
                return true;
            }
            if ($config = $act[$paytype] && $config['is_activity']) {
                //有可用的活动
                if (($config['starttime'] == 0 || $today >= $config['starttime']) && ($config['endtime'] == 0 || $today <= $config['endtime'])) {
                    //活动已经开始
                    if (isset($config['largessmoney'][$amount])) {
                        //有相应的充值赠送活动
                        $map    = array(
                            'payid' => $id
                        );
                        $result = M("payactivitylogs")->where($map)->find();
                        if ($result) {
                            //已经赠送过了
                            return true;
                        }
                        $field  = $config['largessmoney'][$amount]['type'] == 2 ? 'money' : 'egold';
                        $result = M("user")->where("uid=" . $uid)->setInc($field, $config['largessmoney'][$amount]['num']);
                        if ($result) {
                            //赠送成功，写入赠送日志
                            $data = array(
                                "payid"        => $id,
                                "uid"          => $uid,
                                "paymoney"     => $amount,
                                "hongshumoney" => $config['largessmoney'][$amount]['num'],
                                "addtime"      => NOW_TIME,
                                'moneytype'    => $config['largessmoney'][$amount]['type'] == 2 ? 2 : 1
                            );
                            M("payactivitylogs")->data($data)->add();
                        } else {
                            //赠送失败
                            //$this->addSyslog(
                            //    NOW_TIME, 0, '系统', $uid, $data['buyname'], '充值' . $amount . '赠送' . $config['largessmoney'][$amount]['num'] . ($config['largessmoney'][$amount]['type'] == 2 ? C('SITE_CONFIG.MONEY_NAME') : C('SITE_CONFIG.EMONEY_NAME')) . '操作失败'
                            //);
                        }
                    }
                }
            }
        }
        return true;
    }

}
